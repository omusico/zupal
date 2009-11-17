<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Form
 *
 * @author bingomanatee
 */
class Zupal_Fastform_Form
extends Zupal_Fastform_Abstract
{

    public function __construct($pName = '', $pID = NULL, $pLabel = '',
        $pAction = '', $pFields = array(), $pProps = array()) {
        $this->set_name($pName);
        $this->set_label($pLabel);
        $this->set_id($pID);

        if ($pProps && count($pProps)):
            $this->load_props($pProps);
        endif;

        if ($pAction):
            $this->set_action($pAction);
        endif;

        if ($pFields && count($pFields)):
            $this->load_fields($pFields);
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ from_config @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @return Zupal_Fastform_Form
 */
    public static function from_config ($pConfig, $name = NULL, $label = NULL, $template = 'Table') {
        if (is_string($pConfig)):
            $pConfig = new Zend_Config_Ini($pConfig, 'fields');
        endif;
        if ($pConfig instanceof Zend_Config):
            $pConfig = $pConfig->toArray();
        endif;

        $action = (array_key_exists('action', $pConfig)) ? $pConfig['action'] : '';

        if(array_key_exists('name', $pConfig)) $name = $pConfig['name'];

        $form = new Zupal_Fastform_Form($name, $pName, $pLabel, $pAction, $pConfig['elements']);

        return $form;
    }

}

