<?

class Administer_Meta_MVC {
    function __construct($m, $c, $a = '') {
        $this->set_module($m);
        $this->set_controller($c);
        $this->set_action($a);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controllers @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pModule
     * @return <type>
     */
    public function controllers ($pModule = '', $full = FALSE) {
        $module = $pModule ? $pModule : $this->get_module();
        
        $di = new DirectoryIterator(APPLICATION_PATH . '/modules/' . $module . '/controllers');
	$out = array();
        
	foreach($di as $fi):
            $fin = $fi->getFilename();
	    if ((!$fi->isDot()) && $fi->isFile()):
                if (preg_match('~^(.*)Controller.php~', $fin, $matches)):
                    if ($full):
                        $out[$matches[1]] = $fi;
                    else:
                        $out[] = $matches[1];
                    endif;
                endif;
	    endif;
	endforeach;
        return $out;      
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ modules @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pParam
     * @return string[]
     */
    public function modules () {
        $di = new DirectoryIterator(APPLICATION_PATH . '/modules');
	$out = array();
        
	foreach($di as $fi):
	    if ((!$fi->isDot()) && $fi->isDir() && (!$fi->isDot())):
		$out[] = $fi->getFilename();
	    endif;
	endforeach;
        return $out;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ module @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_module = null;
    /**
     * @return class;
     */

    public function get_module() { return $this->_module; }

    public function set_module($pValue) { $this->_module = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ controller @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_controller = null;
    /**
     * @return class;
     */

    public function get_controller() { return $this->_controller; }

    public function set_controller($pValue) { $this->_controller = $pValue; }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ params @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_params = array();

    public function set_param($pValue, $pID = NULL) {
        if (is_null($pID)):
            array_push($this->_params, $pValue);
        else:
            $this->_params[$pID] = $pValue;
    endif;
    }

    public function get_param($pID) { return $this->_params[$pID]; }

    public function get_params() { return $this->_params; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_params @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pParams
     * @return <type>
     */
    public function set_params ($pParams) {
        if (!$pParams):
            return;
        elseif (is_array($pParams)):
            $params = $pParams;
        elseif (is_string($pPrams)):
            $params = split("\n", $pParams);
            if ($params && count($params)):
                foreach($params as $i => $param):
                    $param = trim($param);
                    if (preg_match('~,~', $param)):
                        $params[$i] = split(',', $param);
                    else:
                        $params[$i] = $param;
                endif;
                endforeach;
            else:
                $params = array();
            endif;
        endif;
        $this->_params = $params;
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ controller_file @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_controller_file() {
        return $this->controller_class_name() . '.php';
    }

    public function set_controller_file($pValue) {
        if (preg_match('~(.*)Controller\.php~', $pValue, $match)):
            $this->set_controller($match[1]);
    endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ action @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_action = null;
    /**
     * @return class;
     */

    public function get_action() { return $this->_action; }

    public function set_action($pValue) { $this->_action = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_controller_file @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pParam
     * @return <type>
     */
    public function get_controller_class_object () {

        if (file_exists($this->controller_path())):
            $zrf = new Zend_Reflection_File($this->controller_path());
            $crf = array_shift($zrf->getClass($this->controller_class_name()));
            $class = new Zend_CodeGenerator_Php_Class($crf);
        else:
            $params = array(
                'name' => $this->controller_class_name(),
                'extendsClass' => 'CPF_Controller_Abstract'
            );

            if ($this->get_action()):
                $mp = array(
                    'name' => $this->get_action() . 'Action'
                );
                $action = new Zend_CodeGenerator_Php_Method($mp);
            endif;

            $class = new Zend_CodeGenerator_Php_Class($params);
        endif;
        return $class;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_class_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     *
     * @return <type>
     */
    public function controller_class_name () {
        return ucfirst($this->get_controller()) . 'Controller';
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_path @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function controller_path () {
        return APPLICATION_PATH . '/modules/' . $this->get_module() . '/controllers/' . $this->controller_file();
    }
}