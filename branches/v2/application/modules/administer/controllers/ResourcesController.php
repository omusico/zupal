<?php

/**
 * Note this controller is not secure by default; actions must be individually secured.
 */

class Administer_ResourcesController extends Zupal_Controller_Abstract {

    public function init() {
        parent::init();
        $this->_helper->layout->disableLayout();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ imageAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function imageAction () {
        $path = ZUPAL_MODULE_PATH . DS . $this->_getParam('image_module', 'NOMODULE') . DS . 'images' . DS . $this->_getParam('image_path', 'NOPATH');


        $path = str_replace(':', DS, $path);
        if (file_exists($path)):
            $this->_helper->viewRenderer->setNoRender(true);
            $pi = pathinfo($path);

            $this->getResponse()
            ->setHeader('Content-Type', 'image/' . strtolower($pi['extension']));

            echo file_get_contents($path);
        else:
            throw new Exception(__METHOD__ . ": cannot find file $path ");
        endif;
    }

}

