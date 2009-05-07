<?

class Content_IndexController extends Zupal_Controller_Abstract
{

	public function preDispatch()
	{
		parent::preDispatch();

		$menu = new Zupal_Menu();
		$item = new Zupal_Menu_Item('Content', 'content', 'index', 'index');
		$menu->set_item($item);
		$this->view->placeholder('breadcrumb')->set($menu);

	}

	public function indexAction()
	{
		$this->view->content = Zupal_Content::getInstance()->find(array(Zupal_Node_Abstract::LIVE_SELECT));
	}
}