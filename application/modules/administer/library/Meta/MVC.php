<?

class Administer_Lib_Meta_MVC {
    function __construct($m = '', $c = '', $a = '') {
	$this->set_module($m);
	$this->set_controller($c);
	$this->set_action($a);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controllers @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pParam
     * @return string
     */
    public function controllers ($pModule = NULL) {
	$module = $pModule ? $pModule : $this->get_module() ? $this->get_module() : 'default';
        if (!$module):
            throw new Exception(__METHOD__ . ': attempt to find controllers of blank module');
        endif;
	$cont = APPLICATION_PATH . '/modules/' . $module . '/controllers/';

	$di = new DirectoryIterator($cont);
	$out = array();
	foreach($di as $file):
	    if ($file->isFile() && !$file->isDot()):
		if (preg_match('~^(.*)Controller\.php$~', $file->getFilename(), $match)):
		    $out[] = $match[1];
	    endif;
	endif;

	endforeach;
	return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ actions @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pController = NULL, $pModule = NULL
     * @return string
     */
    public function controller_reflection ($pController = NULL, $pModule = NULL) {
	$controller = $pController ? $pController : $this->get_controller();
	$module = $pModule ? $pModule : $this->get_module();

	$controllerfile = ucfirst($controller) . 'Controller.php';

	$path = APPLICATION_PATH . "/modules/$module/controllers/$controllerfile";
	require_once($path);
	return new Zend_Reflection_File($path);
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


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ controller_file @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_controller_file() {
	return ucfirst($this->get_controller()) . 'Controller.php';
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
     * @param string $pParam
     * @return string
     */
    public function get_controller_class_object () {

	if (file_exists($this->controller_path())):
	    $zrf = new Zend_Reflection_File($this->controller_path());
	    $crf = array_shift($zrf->getClass($this->controller_class_name()));
	    $class = new Zend_CodeGenerator_Php_Class($crf);
	else:
	    $params = array(
		'name' => $this->controller_class_name(),
		'extendsClass' => 'Zupal_Controller_Abstract'
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
     * @return string
     */
    public function controller_class_name ($pController = NULL, $pModule = NULL) {
	$controller = $pController ? $pController : $this->get_controller();
	$module = $pModule ? $pModule : $this->get_module();
	if ((!$module) || ($module == 'default')):
	    $prefix = '';
	else:
	    $prefix = ucfirst($module) . '_';
	endif;
	return $prefix . ucfirst($controller) . 'Controller';
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_path @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function controller_path () {
	return APPLICATION_PATH . '/modules/' . $this->get_module() . '/controllers/' . $this->get_controller_file();
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ backup_controller @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string
     * @return string
     */
     private $_backup_path;

    public function backup_controller () {
	$path = $this->controller_path();
	$this->_backup_path = preg_replace('~\/application\/~', '/mvc_backups/', $path). '.' . time();
	error_log(__METHOD__ . ': copying ' . $path . ' to ' . $this->_backup_path);
	if (!is_dir(dirname($this->_backup_path))):
	    mkdir(dirname($this->_backup_path), 0775, TRUE);
	    if (!is_dir(dirname($this->_backup_path))):
		throw new Exception("cannot backup '$path' to '$this->_backup_path'");
	    endif;
	endif;
	error_log(__METHOD__ . ': ' . `echo \$USER`);
	if (!copy($path, $this->_backup_path)):
	    throw new Exception("cannot backup '$path' to '$this->_backup_path' - 2");
	endif;

	return file_get_contents($path);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_action @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pAction
     * @return string
     */
    public function create_action ($pAction = NULL, $pParams = NULL) {
	$action = $pAction ? $pAction : $this->get_action();
        if ($pParams && (array_key_exists('view_body', $pParams))):
            $view_body = $pParams['view_body'];
            unset($pParams['view_body']);
        else:
            $view_body = '';
            if ($pParams && is_array($pParams) && count($pParams)):
                foreach($pParams as $param):
                    if (is_array($param)):
                        list($name, $alias, $default) = $param;
                            $default = trim($default);
                    elseif (!trim($param)):
                        continue;
                    else:
                        $name = $alias = trim($param);
                        $default = NULL;
                    endif;
                    $name = trim($name);
                    if (!$name):
                        continue;
                    endif;
                    ob_start(); ?>
    $this-><?= $name ?>;
    
<?
                    $view_body .= ob_get_clean();
                endforeach;
            endif;
        endif;
        
	$file = $this->controller_reflection();
	$c = $file->getClass($this->controller_class_name());

	$aname = "{$action}Action";
	$class = Zend_CodeGenerator_Php_Class::fromReflection($c);

	if (!$class->hasMethod($aname)):
	    $body = '';
	    $reflect = '';

	    if ($pParams && is_array($pParams) && count($pParams)):
		ob_start();
		foreach($pParams as $param):
		    if (!trim($param)):
			continue;
		    elseif (is_array($param)):
			list($name, $alias, $default) = $param;
			    $default = trim($default);
		    else:
			$name = $alias = trim($param);
			$default = NULL;
		    endif;
		    $name = trim($name);
		    if (!$name):
			continue;
		    endif;
		?>
<? printf('$%s = $this->_getParam("%s", %s); ', $name, $alias, is_null($default) ? ' NULL ' : "'$default'" ); ?>
<? ob_start(); ?>
<? printf('$this->view->%s = $%s;', $name, $name); ?>
    
<? $reflect .= ob_get_clean(); ?>
    <?
		endforeach;
		$body =  ob_get_clean() . "\n" . $reflect;
	    endif;

	    $old = $this->backup_controller();

	    $method = new Zend_CodeGenerator_Php_Method();
	    $method->setName($aname)
		   ->setBody($body);
	    $class->setMethod($method);

	    $file = new Zend_CodeGenerator_Php_File();
	    $file->setClass($class);

	    $new_file = preg_replace('~[\r\n]{2}~', "\r\n", $file->generate());

	    file_put_contents($this->controller_path(), $new_file);
	    $view_path = $this->view_path($action);
	    if (!file_exists($view_path)):
		$dir = dirname($view_path);
		if (!is_dir($dir)):
		    mkdir($dir, 0775, TRUE);
		endif;

		file_put_contents($view_path, "<?\n\$this->placeholder('title')->set('');\n$view_body\n");
	    endif;

	    $exec = "diff {$this->_backup_path} {$this->controller_path()} ";

	    $diff = shell_exec($exec);

	    return array('old' => $old, 'new' => $new_file, 'diff' => $diff,
		'backup_path' => $this->_backup_path, 'controller_path' => $this->controller_path());
	endif;		
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ view_path @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string
     * @return string
     */
    public function view_path ($pAction = NULL) {
	$action = $pAction ? $pAction : $this->get_action();
	return APPLICATION_PATH . '/modules/' . $this->get_module() .
	    '/views/scripts/' . $this->get_controller() . '/' . $action . '.phtml';
    }
}
