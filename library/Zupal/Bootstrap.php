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

        $backendOptions = array('cache_dir' => ZUPAL_ROOT_DIR . DS . 'cache');
	//	print_r($backendOptions);
		
        // getting a Zend_Cache_Core object
        $cache = Zend_Cache::factory('Core','File',$frontendOptions,$backendOptions);
        self::$registry->cache = $cache;
    }
    
    public static function setupEnvironment()
    {
        date_default_timezone_set('America/Los_Angeles');	   
        require_once('Zend/Loader.php');
        Zend_Loader::registerAutoload();
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
        self::$frontController->registerPlugin(new Zend_Controller_Plugin_ErrorHandler());
        self::$frontController->addModuleDirectory(APPLICATION_PATH . DS . "modules");
        self::$frontController->setParam('registry', self::$registry);
    }

    public static function setupView()
    {
        // Initialise Zend_Layout's MVC helpers
	//	print_r(self::$registry);
		$layout = self::$registry->configuration->layout;
        Zend_Layout::startMvc(array('layoutPath' => APPLICATION_PATH . "/layouts", 'layout' => $layout));
        
        // VIEW SETUP - Initialize properties of the view object
        // The Zend_View component is used for rendering views. Here, we grab a "global" 
        // view instance from the layout object, and specify the doctype we wish to 
        // use. In this case, XHTML1 Strict.
        $view = Zend_Layout::getMvcInstance()->getView();
        $view->addHelperPath(LIBRARY_PATH . '/Zupal/View/Helper', 'Zupal_View_Helper');
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
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/config/configuration.xml', APPLICATION_ENV, true);
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