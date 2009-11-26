<?php

abstract class Zupal_Form_Abstract
extends Zend_Form {

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_domain = NULL;
    /**
     * @return Zupal_Domain_IDomain
     */
    public function get_domain() {
        if(!$this->_domain):
            $dc = $this->get_domain_class();
            $this->_domain = new $dc();
        endif;
        $data = $this->_domain->toArray();
        return $this->_domain;
    }

    public function set_domain($value) {
        if ($value):
            if (!is_object($value)):
                $class = $this->get_domain_class();
                $value = new $class($value);
            endif;
        else:
            $value = NULL;
        endif;
        
        $this->_domain = $value;
        $this->domain_to_fields();
    }

    protected function _atom_load()
    {
        $domain = $this->get_domain();
        $this->title->setValue($domain->get_title());
        $this->lead->setValue($domain->get_lead());
        $this->content->setValue($domain->get_content());
        $this->format_lead->setChecked($domain->get_format_lead());
        $this->format_content->setChecked($domain->get_format_content());
        if ($domain->get_format_lead()):
            $this->lead->setAttrib('class', 'ckeditor');
        endif;
        if ($domain->get_format_content()):
            $this->content->setAttrib('class', 'ckeditor');
        endif;
    }

    protected function _save_atom()
    {
        $this->get_domain()->set_title($this->title->getValue());
        $this->get_domain()->set_lead($this->lead->getValue());
        $this->get_domain()->set_content($this->content->getValue());
        $this->get_domain()->set_format_content($this->format_content->getValue());
        $this->get_domain()->set_format_lead($this->format_lead->getValue());
        $this->get_domain()->save();

    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    protected abstract function get_domain_class ();
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ translation @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     *
     * If your form has more complex relationships,
     * override these methods to allocate field/domain values
     *
     * @param Zupal_Domain_IDomain $pObject
     * @param array $pFields
     */
    protected function domain_to_fields(array $pFields = NULL) {
        $object = $this->get_domain();

        if (!is_array($pFields)):
            $pFields = $this->domain_fields();
        endif;

        foreach($pFields as $field):

            $element = $this->getElement($field);
            if ($element):
                $element->setValue($object->$field);
            else:
                $object->$field;
                throw new Exception(__METHOD__ . ": Cannot find $field");

        endif;
        endforeach;
    }
/**
 *
 * @param array $pFields
 */
    protected function fields_to_domain(array $pFields = NULL) {
        $object = $this->get_domain();

        if (!is_array($pFields)):
            $pFields = $this->domain_fields();
        endif;

        foreach($pFields as $field):
            $element = $this->getElement($field);
            if ($field == $object->table()->idField()):
                continue;
            endif;
            $object->$field = $element->getValue();
        endforeach;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ domain_fields @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public abstract function domain_fields ();

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function save () {
        $this->fields_to_domain();
        $this->get_domain()->save();
    }
    
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ isValid @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pParam
     */
    public function isValid ($pParam) {
        return parent::isValid(stripslashes_deep($pParam));
    }

}

function stripslashes_deep($value)
{
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);

    return $value;
}
