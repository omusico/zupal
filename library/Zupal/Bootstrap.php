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

        $backendOptions = array('cache_dir' => ROOT_DIR . DS . 'cache');

        // getting a Zend_Cache_Core object
        $cache = Zend_Cache::factory('Core','File',$frontendOptions,$backendOptions);
        self::$registry->cache = $cache;
    }
    
    public static function setupEnvironment()
    {
    	if(APPLICATION_ENV != 'development') 
    	{ 
        	error_reporting(E_ALL);
        	ini_set("display_errors", "On");
        }
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
        self::setupDatabase();
        self::setupProfiler();
        self::setupSession();
        self::setupAuth();
        self::setupFrontController();
        self::setupView();
    }
    
    public static function prepareConsole() {
        self::setupEnvironment();
        self::setupRegistry();
        self::setupConfiguration();
        self::setupDatabase();
        self::setupProfiler();
    }

    public static function setupProfiler() {
       	$profiler = new Doctrine_Connection_Profiler();
       	Doctrine_Manager::getInstance()->getConnection("default")->setListener($profiler);
		self::$registry->profiler = $profiler;
    }

    public static function setupSession() {
        Zend_Session::start(true);
    }

    public static function setupFrontController()
    {
    	//Zend_Controller_Action_HelperBroker::addPrefix('Zupal_Controller_Action_Helper');
        self::$frontController = Zend_Controller_Front::getInstance();
        self::$frontController->throwExceptions(false);
        self::$frontController->returnResponse(true);
        self::$frontController->registerPlugin(new Zend_Controller_Plugin_ErrorHandler());
        self::$frontController->addModuleDirectory(APPLICATION_PATH . "/modules/");
        self::$frontController->setParam('registry', self::$registry);
    }

    public static function setupView()
    {
        // Initialise Zend_Layout's MVC helpers
        Zend_Layout::startMvc(array('layoutPath' => APPLICATION_PATH . "/layouts"));
        
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
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/config/configuration.xml',APPLICATION_ENV, true);
        self::$registry->configuration = $config;
    }

    public static function setupAuth() { 

        // Configure the instance with constructor parameters...
        $authAdapter = new ZendX_Doctrine_Auth_Adapter(
            Doctrine::getConnectionByTableName('User'),
            'user',
            'username',
            'password',
            'MD5(?)'
        );
        self::$registry->authAdapter = $authAdapter;
                
    }

    public static function setupDatabase()
    {
    	$db = self::$registry->configuration->database->params;

    	$connectionString = sprintf("mysql://%s:%s@%s/%s", 
    								$db->username, 
    								$db->password, 
    								$db->host, 
    								$db->dbname);
    								
		Doctrine_Manager::connection($connectionString, 'default');
		
		self::$registry->doctrine_config = array(
			'data_fixtures_path'  =>  APPLICATION_PATH.'/doctrine/data/fixtures',
			'models_path'         =>  APPLICATION_PATH.'/models',
			'migrations_path'     =>  APPLICATION_PATH.'/doctrine/migrations',
			'sql_path'            =>  APPLICATION_PATH.'/doctrine/data/sql',
			'yaml_schema_path'    =>  APPLICATION_PATH.'/doctrine/schema'
		);  
				
		Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES, true);
    }
}