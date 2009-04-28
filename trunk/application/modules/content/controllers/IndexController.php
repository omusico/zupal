<?

class Content_IndexController extends Zupal_Controller_Abstract
{
	public function indexAction()
	{
		$this->view->content = Zupal_Content::getInstance()->find(array(Zupal_Node_Abstract::LIVE_SELECT));
	}
}