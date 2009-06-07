<?php
class Zupal_Bootstrap
{
    public static $frontController = null;
    public static $registry = null;

    public static function runMVC()
    {
        self::prepareMVC();
        $response = self::$frontController->dispatch();
        self::sendResponse($response);
    }

    public static function setupCache() {
        $frontendOptions = array(
            'lifetime' => self::$registry->configuration->cache->lifetime,
            'automatic_serialization' => true
        );

        $backendOptions = array('cache_dir' => ZUPAL_ROOT_DIR . DS . self::$registry->configuration->cache->path);
	//	print_r($backendOptions);
		
        // getting a Zend_Cache_Core object
        $cache = Zend_Cache::factory('Core','File',$frontendOptions,$backendOptions);
        self::$registry->cache = $cache;
    }
    
    public static function setupEnvironment()
    {
        date_default_timezone_set('America/Los_Angeles');	   
        /*require_once('Zend/Loader.php');
        Zend_Loader::registerAutoload(); */
		require_once 'Zend/Loader/Autoloader.php';
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->setFallbackAutoloader(true);
    }

    public static function prepareMVC()
    {
        self::setupEnvironment();
        self::setupRegistry();
        self::setupConfiguration();
        self::setupCache();
    //    self::setupProfiler();
        self::setupSession();
        self::setupAuth();
        self::setupFrontController();
        self::setupModel();
        self::setupView();

		$manager = Zupal_Module_Manager::getInstance();
		$em = $manager->getEnabledModules();
		foreach($em as $module):
			foreach($module->plugins() as $plugin):
		        self::$frontController->registerPlugin($plugin);
			endforeach;
		endforeach;
    }
    
    public static function prepareConsole() {
        self::setupEnvironment();
        self::setupRegistry();
        self::setupConfiguration();
        self::setupModel();
    //    self::setupProfiler();
    }

    public static function setupProfiler() {
       //	$profiler = new Doctrine_Connection_Profiler();
     //  	Doctrine_Manager::getInstance()->getConnection("default")->setListener($profiler);
	//	self::$registry->profiler = $profiler;
    }

    public static function setupSession() {
        Zend_Session::start(true);
    }

    public static function setupFrontController()
    {
    	//Zend_Controller_Action_HelperBroker::addPrefix('Zupal_Controller_Action_Helper');
        self::$frontController = Zend_Controller_Front::getInstance();
        self::$frontController->throwExceptions(true);
        self::$frontController->returnResponse(true);
        self::$frontController->registerPlugin(new Zupal_Plugins_Pagerouter());
        self::$frontController->registerPlugin(new Zend_Controller_Plugin_ErrorHandler());

        self::$frontController->addModuleDirectory(ZUPAL_APPLICATION_PATH . DS . "modules");
        self::$frontController->setParam('registry', self::$registry);
		if (self::$registry->configuration->baseurl):
			self::$frontController->setBaseUrl(self::$registry->configuration->baseurl);
		endif;
		defined('ZUPAL_BASEURL') ||
			define('ZUPAL_BASEURL', self::$frontController->getBaseUrl());
    }

    public static function setupView()
    {
        // Initialise Zend_Layout's MVC helpers
	//	print_r(self::$registry);
		$layout = self::$registry->configuration->layout;
        Zend_Layout::startMvc(array('layoutPath' => ZUPAL_LAYOUT_PATH, 'layout' => $layout));
        
        // VIEW SETUP - Initialize properties of the view object
        // The Zend_View component is used for rendering views. Here, we grab a "global" 
        // view instance from the layout object, and specify the doctype we wish to 
        // use. In this case, XHTML1 Strict.
        $view = Zend_Layout::getMvcInstance()->getView();
        $view->addHelperPath(ZUPAL_LIBRARY_PATH . '/Zupal/View/Helper', 'Zupal_View_Helper');

		foreach(Zupal_Module_Manager::getInstance()->getModuleNames() as $name):
			$view->addHelperPath(
				ZUPAL_MODULE_PATH . DS . $name . DS . 'views'. DS . 'helpers',
				'Zupal_' . ucfirst($name) . '_Helper');
		endforeach;
        $view->doctype('XHTML1_STRICT');
    }

    public static function sendResponse(Zend_Controller_Response_Http $response)
    {
        $response->sendResponse();
    }

    public static function setupRegistry()
    {
        self::$registry = new Zend_Registry(array(), ArrayObject::ARRAY_AS_PROPS);
        Zend_Registry::setInstance(self::$registry);
    }

    public static function setupConfiguration()
    {
        $config = new Zend_Config_Xml(ZUPAL_APPLICATION_PATH . '/config/configuration.xml', APPLICATION_ENV, true);
        self::$registry->configuration = $config;
    }

    public static function setupAuth() 
    { 
		//@TODO;
                
    }

    public static function setupModel()
    {
		Zupal_Database_Manager::init();

		Zupal_Module_Manager::getInstance()->load_all();
		foreach(Zupal_Module_Manager::getInstance()->get_all() as $item)
		{
			$item->add_paths();
		}
    }
}