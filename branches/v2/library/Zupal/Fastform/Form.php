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

        $form = new Zupal_Fastform_Form($name, $pName, $label, $action, $pConfig['elements']);

        return $form;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ isValid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * extned to enforce tighter validation. 
     *
     * @return boolean
     */
    public function isValid ($pParams = NULL) {

        if (!is_null($pParams) && is_array($pParams)):
            $this->load_field_values($pParams);
        endif;

        /**
         * @var Zupal_Fastfield_Field_Abstract
         */
        $field = NULL;
        $valid = TRUE;

        foreach($this->get_fields() as $field):
            if ($field->get_required()):
                if (((string) $field->get_value()) == ''):
                    $field->set_error('Required');
                    $valid = FALSE;
                endif;
            endif;
        endforeach;

        return $valid;
    }

/**
 * overload to create config based
 * @return string;
 */
    protected function _ini_path(){
        return ''; // return preg_replace('~php$~', 'ini', __FILE__);
     }

}

