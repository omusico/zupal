<?php

class Form_Zupalusers
extends Zupal_Fastform_Domainform
{


    protected function _domain_class()
    {
        return "Model_Zupalusers";
    }

    protected function _ini_path(){
         return preg_replace('~php$~', 'ini', __FILE__);
     }
}

