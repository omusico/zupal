<?php
if (!$mod->init || 1) {
    foreach($mod->profile['page_layouts'] as $layout_def) {
        $linst = Page_Model_Layouts::instance();
        //    $lmod = Zupal_Module_Model_Mods::instance()->mod('page');
        if (!$linst->has($layout_def)) {
            $l = new Page_Model_Layouts($layout_def);
            $l->save();
        }
    }
}

