<?php

class Administer_IndexController extends Zupal_Controller_Abstract
{

    /**
     * 
     */
        public function preDispatch() {
            $u = Model_Users::current_user();

            if (!$u || ! $u->can('site_admin')):
                $param = array('error' => 'This area is reserved for administrators');
                $this->_forward('insecure', 'error', 'administer', $param);

            endif;
        }

    /**
     * 
     */
    public function init()
    {
        $this->_helper->layout->setLayout('admin');
        parent::init();
    }

    /**
     * 
     */
    public function indexAction()
    {
        $this->view->placeholder( 'page_title' )->set('Administer');
    }

    public function repairatomsAction()
    {
        foreach(Pages_Model_Zupalpages::getInstance()->findAll() as $page) $page->get_atom();
        foreach(Ultimatum_Model_Ultgroups::getInstance()->findAll() as $group)
        {
            /**
             * @var Model_Zupalatoms
             */
            $atom = $group->get_atom();
            if (!$atom->get_format_lead()) $atom->strip_lead_markup();
            if (!$atom->get_format_content()) $atom->strip_content_markup();
        }
        $this->_forward('atoms');
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ atomsAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function atomsAction () {
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ atomstoreAction @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function atomsstoreAction () {
        $atoms = Model_Zupalatoms::getInstance()->findAll('atomic_id');

        $data = array();

        foreach($atoms as $atom):
            $row = $atom->toArray();
            $parent = $atom->parent();
            $row['parent_id'] = $parent ? $parent->identity() : 0;
            $data[] = $row;
        endforeach;

        $this->_store('id', $data);
    }
}

