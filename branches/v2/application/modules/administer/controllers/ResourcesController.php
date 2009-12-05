<?php

/**
 * Note this controller is not secure by default; actions must be individually secured.
 */

class Administer_ResourcesController extends Zupal_Controller_Abstract {

    public function init() {
        parent::init();
        $this->_helper->layout->disableLayout();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ storeAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function storeAction () {
        $model = $this->_getParam('model_class');
        $m = new $model();
        if (method_exists($m, 'store')):
            $data = $m->store();
        else:
            $sql = sprintf ('SELECT * from %s;', $m->table()->tableName());
            error_log(__METHOD__ . ': SQL = ' . $sql);
            $data = $m->table()->getAdapter()->fetchAssoc($sql);
        endif;

        $id = $m->table()->idField();

        $this->_store($id, $data);
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
    public function styleAction () {
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
/* @@@@@@@@@@@@@ EXTENSION BOILERPLATE @@@@@@@@@@@@@@ */

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ controller_dir @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function controller_dir () {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ dialogAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function dialogAction () {
        foreach($this->_getAllParams() as $param => $value):
            switch ($param):
                case 'question':
                case 'options':
                    $this->view->$param = $value;
                    break;
            endswitch;
        endforeach;
    }

   /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ itembuttonsAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function buttonlistAction () {

        $this->view->buttons = $this->getParam('buttons');
        $this->view->prefix = $this->getParam('prefix', '');
        $this->view->suffix = $this->getParam('suffix', '');
        
    }
}

