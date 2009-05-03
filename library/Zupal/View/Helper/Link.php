<?php
/**
 *
 * @author daveedelhart
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * link helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zupal_View_Helper_Link
{
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;

	/**
	 *  
	 */
	public function link ($pLabel, array $pParams)
	{
		$module = 'index';
		$action = 'index';
		$controller = 'index';
		$class = '';

		foreach(array('module', 'action', 'controller', 'class') as $prop):
			if (array_key_exists($prop, $pParams)):
				$$prop = $pParams[$prop];
				unset($pParams[$prop]);
			endif;
		endforeach;

		$path = array($controller, $action);
		if ($module):
			array_unshift($path, $module);
		endif;

		$path = array_merge($path, $pParams);
	

		// TODO Auto-generated Zend_View_Helper_link::link() helper 
		ob_start();
?>
<a class="<?= $class ?>" href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/<?= join(',', $path) ?>"><?= $pLabel ?></a>
<?
		return ob_get_clean();
	}

	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView (Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}
