<?php

class IndexController extends Zupal_Controller_Abstract
{


    public function indexAction()
    {
		$this->view->params = $this->_getAllParams();
    }

}





