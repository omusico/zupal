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

	const IMG = '<img src="%s" />';
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;

	/**
	 *  
	 */
	public function link ($label, array $pParams)
	{
		$module = 'index';
		$action = 'index';
		$controller = 'index';
		$class = '';
		$title =  '';
		foreach(array('module', 'action', 'controller', 'class', 'title') as $prop):
			if (array_key_exists($prop, $pParams)):
				$$prop = $pParams[$prop];
				unset($pParams[$prop]);
			endif;
		endforeach;

		foreach(array('class', 'title') as $prop):
			if ($$prop):
				$$prop = $prop . ' ="' . $$prop . '" ';
			endif;
		endforeach;

		$path = array($controller, $action);
		if ($module):
			array_unshift($path, $module);
		endif;

		foreach($pParams as $k => $v):
			$path[] = $k;
			$path[] = $v;
		endforeach;

		if (preg_match('~^\((.*)\)~', $label, $matches)):
			$src = self::icon_root() . $matches[1] . '.gif';
			$label = sprintf(self::IMG, $src);
		endif;

		// TODO Auto-generated Zend_View_Helper_link::link() helper 
		ob_start();
?>
<a <?= $class ?> <?= $title ?> href="<?= Zend_Controller_Front::getInstance()->getBaseUrl() ?>/<?= join(DS, $path) ?>"><?= $label ?></a>
<?
		$out = ob_get_clean();
		return $out;
	}

	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView (Zend_View_Interface $view)
	{
		$this->view = $view;
	}

	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ icon_root @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_icons = array(
		'x', 'off', 'up', 'down', 'edit', 'list', 'ok'
	);

	private static $_icon_root = NULL;
	function icon_root()
	{
		if (!self::$_icon_root):
		// process
			self::$_icon_root = Zend_Controller_Front::getInstance()->getBaseUrl() . DS . 'img' . DS . 'icons' . DS;
		endif;
		return self::$_icon_root;
	}
}
