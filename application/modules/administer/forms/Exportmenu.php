<?php

/**
 * Description of Exportmenu
 *
 * @author bingomanatee
 */
class Administer_Form_Exportmenu
extends Zupal_Fastform_Abstract
{

    protected function _ini_path(){ return preg_replace('~php$~', 'ini', __FILE__); }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _filter_configuration @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * An optional extenstion point for any field customization.
     *
     * @param array $pConfig_array
     * @return <type>
     */
    protected function _filter_config (array $pConfig_array) {
        $indexed = Administer_Model_Modules::find_all_indexed();
        
        foreach($indexed as $key => $module):
            $pConfig_array['elements']['root_module']['options']['multiOptions'][$key] = $module->title;
        endforeach;
        return $pConfig_array;
    }
}
