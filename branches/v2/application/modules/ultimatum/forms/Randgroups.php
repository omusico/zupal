<?

class Ultimatum_Form_Randgroups
extends Zend_Form
{
    public function __construct() {
        $options = new Zend_Config_Ini(dirname(__FILE__) . '/Randgroups.ini', 'fields');
        parent::__construct($options);
    }
}