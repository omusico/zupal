<?

class Administer_Form_Userfind
extends Zend_Form
{

    public function __construct()
    {
        $filename = preg_replace('~php$~', 'ini', __FILE__);
        $ini = new Zend_Config_Ini($filename, 'fields');
        parent::__construct($ini);
    }
    
} 