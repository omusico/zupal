<?

abstract class Zupal_Controller_Abstract extends Zend_Controller_Action {
    protected $security = 0;
    protected $insecure = FALSE;
    protected $identity = NULL;
    
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
  
    
    public function init() {
        
        $module = $this->getRequest()->getModuleName();
        $config = array(
            'basePath' => APPLICATION_PATH . '/library/' . $module,
            'namespace' => ucfirst($module)
        );
        
        $loader = new Zend_Loader_Autoloader_Resource($config);
        
        $this->view->addHelperPath(APPLICATION_PATH . '/modules/default/views/helpers/Zupal', 'Zupal_Helper');
        $this->view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
        
        parent::init();
    }
    
    public function preDispatch() {
        $message = $this->_getParam('message', '');
        $error = $this->_getParam('error', '');
        if ($message || $error):
            $this->view->placeholder('message')->set($message);
            $this->view->placeholder('error')->set($error);
        endif;
    }
    
    public function postDispatch() {
        $this->view->headTitle()->set($this->view->placeholder('page_title'));
    }
      /**
     *
     */
    public function indexAction () {
        
        /**
         * most index pages are all view -- so a stub is created here for conveniene.
         */
    }

    
        /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _store @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pID, $pData, $pLabel
     * @return void
     */
    public function _store ($pID, $pData, $pLabel) {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $zd = new Zend_Dojo_Data($pID, $pData, $pLabel);
        echo $zd->toJson();
    }

    protected function _getAllParams()
    {
        return Zupal_Util_Array::stripslashes($this->getRequest()->getParams());
    }

    public function __call($methodName, $args) {
        if (preg_match('~^(.*)Action$~', $methodName, $m)):
            $action = $m[1];
            $caname = ucfirst(strtolower($action));

            if (preg_match('~^(.*)(execute|response|items|store|newitem|newresponse|edititem|editresponse|deleteitem|deleteresponse)~$~', $caname, $m)):
                $caname = $m[1];
                $response = TRUE;
            else:
                $response = FALSE;
            endif;

            $cn = $this->controller_name($this);
            $cd = $this->controller_dir();
            
            $action_class_path = $cd . $cn . DIRECTORY_SEPARATOR . $caname . 'Action.php';
            if (file_exists($action_class_path)):
                require_once($action_class_path);

                $action_class = $this->module_name($this) . '_' .  $cn . '_' . $caname . 'Action';
                $action = new $action_class($this);

                return $response ? $action->response() :  $action->run();
            endif;
        endif;
        return parent::__call($methodName, $args);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_dir @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    abstract public function controller_dir ();
/**
 * This boilerplate should work with any controller
 *
     public function controller_dir () {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }
*/
    
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    private $_controller_name = NULL;
    function controller_name($pController_Object) {
        if (is_null($this->_controller_name)):
        // process
            if (preg_match('~^[\w]+_([\w]+)Controller$~', get_class($pController_Object), $m)):
                $value = $m[1];
            endif;
            $this->_controller_name = $value;
        endif;
        return $this->_controller_name;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ module_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    private $_module_name = NULL;
    function module_name($pController_Object) {
        if (is_null($this->_module_name)):
        $value = array_shift(split('_', get_class($pController_Object)));
        // process
            $this->_module_name = $value;
        endif;
        return $this->_module_name;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ forward @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * exposes forward as a public method for the benefit of extnesions.
     * @return void
     */
    public function forward ($p1, $p2 = NULL, $p3 = NULL, $p4 = NULL) {
        return $this->_forward($p1, $p2, $p3, $p4);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ getParam @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param string $pParam
 * @return scalar
 */
    public function getParam ($paramName, $pDefault = NULL) {
        return $this->_getParam($paramName, $default);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ getAllParams @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return array
     */
    public function getAllParams () {
        return $this->_getAllParams();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ helper @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     */
    public function helper () {
        return $this->_helper;
    }
}
