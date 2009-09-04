<?php

class Model_Zupalacl extends Zupal_Domain_Abstract
{

    private static $_instance = 'zupal_acl';

    public function tableClass()
    {
        return 'Model_DbTable_Acl';
    }

    public function get($pID = 'NULL', $pLoadFields = NULL)
    {
        $out = new self($pID);
            if ($pLoad_Fields && is_array($pLoad_Fields)):
                $out->set_fields($pLoad_Fields);
            endif;
            return $out;
    }


}

