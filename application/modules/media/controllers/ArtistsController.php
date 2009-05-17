<?

class Media_ArtistsController
extends Zupal_Controller_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ newAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	*/
	public function newAction ()
	{
		$this->view->form = new Zupal_Media_Artists_Form(new Zupal_Media_Artists());
	}

}