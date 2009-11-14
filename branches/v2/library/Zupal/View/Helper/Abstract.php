<?php
abstract class Zupal_View_Helper_Abstract
extends Zend_View_Helper_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ View @@@@@@@@@@@@@@@@@@@@@@@@ */

    private $view = null;

    public function getView() { return $this->view; }

    public function setView($pValue) { $this->view = $pValue; }


}