<?php

class Pages_Model_Zupalpagestatuses extends Zupal_Domain_Abstract
{

    private static $_instance = 'zupal_pagestatuses';

    public function tableClass()
    {
        return 'Pages_Model_DbTable_Zupalpagestatuses';
    }

    public function get($pID = 'NULL', $)
    {
        $out = new self($pID);
            if ($pLoad_Fields && is_array($pLoad_Fields)):
                $out->set_fields($pLoad_Fields);
            endif;
            return $out;
    }


}

