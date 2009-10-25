<?

class Pages_Form_EditCP extends Zend_Form
{
    public function __construct($id) {
        $options = new Zend_Config_Ini(dirname(__FILE__) . '/EditCP.ini', 'fields');
        parent::__construct($options);
        $this->id->setValue($id);
    //    $this->setAction('/admin/pages');
    }
}