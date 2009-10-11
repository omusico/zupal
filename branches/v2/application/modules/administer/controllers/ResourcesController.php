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
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ scriptAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function scriptAction () {
        $path = ZUPAL_MODULE_PATH . DS . $this->_getParam('script_module', 'NOMODULE') . DS . 'scripts' . DS . $this->_getParam('script_path', 'NOPATH');
        $path = str_replace(':', DS, $path);
        if (file_exists($path)):
            $this->_helper->viewRenderer->setNoRender(true);
            $pi = pathinfo($path);

            $this->getResponse()
            ->setHeader('Content-Type', 'text/javascript');

            echo file_get_contents($path);
        else:
            throw new Exception(__METHOD__ . ": cannot find file $path ");
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ cssAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function cssAction () {
        $path = ZUPAL_MODULE_PATH . DS . $this->_getParam('style_module', 'NOMODULE') . DS . 'style' . DS . $this->_getParam('style_path', 'NOPATH');
        $path = str_replace(':', DS, $path);

        if (file_exists($path)):
            $this->_helper->viewRenderer->setNoRender(true);
            $pi = pathinfo($path);

            $this->getResponse()
            ->setHeader('Content-Type', 'tert/css');

            echo $this->_base_path(file_get_contents($path));
        else:
            throw new Exception(__METHOD__ . ": cannot find file $path ");
        endif;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _base_path @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $css
     * @return string
     */
    public function _base_path ($css) {
        return str_replace('[BASE_URL]', $this->view->baseUrl(), $css);
    }
}
