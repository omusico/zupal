<?php

class Administer_MetaController extends Zupal_Controller_Abstract
{
    public function init () {
        $this->_helper->layout->setLayout('admin');
        parent::init();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ indexAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function indexAction () {

	$di = new DirectoryIterator(APPLICATION_PATH . '/modules');

	$this->view->modules = array();
	foreach($di as $fi):
	    if ((!$fi->isDot()) && $fi->isDir() && (!preg_match('~^\.~', $fi->getFilename()))):
		$this->view->modules[] = $fi->getFilename();
	    endif;
	endforeach;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ adddomainAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function adddomainAction () {
	$module = $this->_getParam('module_domain');

	if (!$module):
	    return $this->_forward('error', NULL, NULL, array('error' => 'No Module Requested'));
	endif;


	if (!$this->_find_or_make_module($module)):
	    return $this->_forward('error', NULL, NULL, array('error' => 'Cannot make module  ' . $module));
	endif;

	$target_dir .= 'models/' . Administer_Meta_Domain::TABLE_FOLDER . '/';

	if (!$this->_find_or_make($target_dir)):
	    return $this->_forward('error', NULL, NULL, array('error' => 'Cannot make module MODEL directory ' . $target_dir));
	endif;

	$this->view->tables = array();

	$adapter = CPF_data::get_adapter();

	$this->view->tables = $adapter->fetchCol('SHOW TABLES');
	$this->view->domain_module = $module;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ adddomainAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function addformAction () {
	$module = $this->_getParam('module_domain');

	if (!$module):
	    return $this->_forward('error', NULL, NULL, array('error' => 'No Module Requested'));
	endif;

	$target_dir = APPLICATION_PATH . '/modules/' . $module . '/';

	if (!$this->_find_or_make_module($module)):
	    return $this->_forward('error', NULL, NULL, array('error' => 'Cannot make module directory ' . $target_dir));
	endif;

	// note -- find or make module should also crate a forms folder

	$this->view->tables = array();

	$adapter = CPF_data::get_adapter();

	$this->view->tables = $adapter->fetchCol('SHOW TABLES');
	$this->view->domain_module = $module;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ adddomaincreateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function adddomaincreateAction () {
	$domain = new Administer_Meta_Domain($this->_getParam('table'), $this->_getParam('module_domain'));
	if (!file_exists($domain->get_domain_path())):
	    $domain->create_domain();
	endif;

	if (!file_exists($domain->get_table_path())):
	    $domain->create_table();
	endif;

	$this->view->domain = $domain;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ adddomaincreateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function addformcreateAction () {
	$domain = new Administer_Meta_Domain($this->_getParam('table'), $this->_getParam('module_domain'));
	if (!file_exists($domain->get_form_ini_path())):
	    $domain->create_form_ini();
	endif;

	if (!file_exists($domain->get_form_path())):
	    $domain->create_form();
	endif;

	$this->view->domain = $domain;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ addformentryAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function addformentryAction () {
	$module = $this->_getParam('module_domain');
	if (!$module):
	    return $this->_forward('error', NULL, NULL, array('error' => 'No Module Requested'));
	endif;

	$this->view->entry_module = $module;
	$this->view->controller_dir = APPLICATION_PATH . '/modules/' . $module . '/controllers';

	$di = new DirectoryIterator($this->view->controller_dir);
	$this->view->controllers = array();

	foreach($di as $file):
	    if ($file->isFile()):
		$this->view->controllers[] = $file->getFilename();
	    endif;
	endforeach;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ addformentrycreateAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function addformentrycreateAction () {
	$entry_module = $this->_getParam('entry_module');

	if ($new_controller = $this->_getParam('new_controller')):
	    $new_action = $this->_getParam('new_action');
	    $this->_make_form_entry($entry_module, $new_controller, $new_action);
	endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _make_form_entry @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @param string $pModule
 * @param string $pController
 * @param string $pAction
 * @return string
 */
    private function _make_form_entry ($pModule, $pController, $pAction) {
	$mmvc = new Administer_Lib_Meta_MVC($pModule, $pController);
	$mmvc->add_action($pAction);
	return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _find_or_make @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pPath
     * @return string
     */
    public function _find_or_make ($pPath) {
	if (is_dir($pPath)):
	    return TRUE;
	endif;

	mkdir($pPath, NULL, TRUE);

	if (is_dir($pPath)):
	    return TRUE;
	else:
	    return FALSE;
	endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _find_or_make @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function _find_or_make_Module ($pModule) {
	$root = APPLICATION_PATH . '/modules/' . $pModule;

	if ($this->_find_or_make($root)):
	    foreach(array('controllers', 'views', 'models', 'forms') as $dir):
		if (!$this->_find_or_make("$root/$dir")):
		    return FALSE;
		endif;
	    endforeach;
	    return TRUE;
	else:
	    return FALSE;
	endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ addactionAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function addactionAction () {
	$this->view->mvc = new Administer_Lib_Meta_MVC($ma = $this->_getParam('meta_module'));
	$this->view->module_action = $ma;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ addactionexecuteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function addactionexecuteAction () {
	$module = $this->_getParam('add_module');
	$controller = $this->_getParam('add_controller');
	$action = $this->_getParam('add_action');
	$params = split("\n", $this->_getParam('action_params', ''));
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
	$mvc = new Administer_Lib_Meta_MVC($module, $controller, $action, $params);

	$this->view->diff = $mvc->create_action( $action, $params);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ addactionexecuteAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function addlistactionexecuteAction () {
	$module = $this->_getParam('add_module');
	$controller = $this->_getParam('add_controller');
	$action = $this->_getParam('add_action');
        $params = array(
            'grid_name' => $this->_getParam('grid_name'),
            'grid_file' => $this->_getParam('grid_file'),
            'grid_store' => $this->_getParam('grid_store'),
            'grid_url' => $this->_getParam('grid_url'),
            'grid_identifier' => $this->_getParam('grid_identifier')
        );

        $params['view_body'] = <<<PHP_BLOCK
<?
$this->placeholder('page_title')->set('');

$this->headScript()->captureStart();
?>
// any formatting functions
<?
    $this->headScript()->captureEnd();
    $columns = new Zend_Config_Ini(dirname(__FILE__) . '/' . $this->grid_file . '.ini','columns');
?>

<?= $this->dojostore($this->grod_store, $this->baseUrl() . $this->grid_url ) ?>
<?= $this->dojogrid($this->grid_name, $this->grid_store, $columns->toArray(), $this->grid_identifier) ?>

PHP_BLOCK;
        
	$mvc = new Administer_Lib_Meta_MVC($module, $controller, $action);

	$this->view->diff = $mvc->create_action($action, $params);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ addactionafterAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function addactionafterAction () {
	$mode = $this->_getParam('change_source');

	$cp = trim($this->_getParam('controller_path'));
	if (!$cp):
	    throw new Exception(__METHOD__ . ': Cannot find controller path');
	endif;

	switch($mode):
	    case 'accept':
	     break;

	     case 'reject':
		 file_put_contents($cp, stripslashes($this->_getParam('old_text')));
	     break;

	     case 'custom':
		 file_put_contents($cp, stripslashes($this->_getParam('custom_text')));
	     break;
	endswitch;

	return $this->_forward('index');
    }
    
    public function addactionlistAction()
    {        
	$this->view->module_action = $ma = $this->_getParam('meta_module');
	$this->view->mvc = new Administer_Lib_Meta_MVC($ma);
    }

}
