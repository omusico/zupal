<?php

class Pages_Model_Zupalpages
extends Model_Zupalatomdomain
implements Model_ZupalatomIF
{

    public function tableClass()
    {
        return 'Pages_Model_DbTable_Zupalpages';
    }

    public function get($pID = 'NULL', $pLoad_Fields = NULL)
    {
        $out = new self($pID);
            if ($pLoad_Fields && is_array($pLoad_Fields)):
                $out->set_fields($pLoad_Fields);
            endif;
            return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_atomic_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_atomic_id (){
        return $this->atomic_id;
    }

    public function set_atomic_id($pValue){
        $this->atomic_id = $pValue;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ content @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @return class;
     */

    public function get_content() {
        $content = parent::get_content();
        if (!$content):
            if ($path = $this->get_ion('content_file', 1)):
                $content = file_get_contents($path);
                $this->set_content($value);
                return $value;
            endif;
        endif;
        return $content;
    }

    public function set_content($pValue) {
        parent::set_content($pValue);

        if ($path = $this->get_ion('content_file', 1)):
            file_put_contents($path, $pValue);
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_atom @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param boolean $pReload = FALSE
     * @return Model_Zupalatoms
     */
    public function get_atom ($pReload = FALSE) {
        $out = parent::get_atom($pReload);

        if (!$out):
        $out = parent::get_atom($pReload);
            return NULL;
        endif;

        if (! $out->get_model_class() != ($class = get_class($this))):
            $out->set_model_class($class);
        endif;
        
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ for_atomic_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param int $pAtomic_id
     * @return Pages_Model_Zupalpages
     */
    public function for_atom_id ($pAtomic_id) {
        return $this->findOne(array('atomic_id' => $pAtomic_id), 'id DESC');
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_instance = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Pages_Model_Zupalpages
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_instance)):
        // process
            self::$_instance = new self();
        endif;
        return self::$_instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ publish_status @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_publish_status = NULL;
    function get_publish_status($pReload = FALSE) {
        if ($pReload || is_null($this->_publish_status)):
            $value = Pages_Model_Zupalpagestatuses::getInstance()->get($this->publish_status);
        // process
            $this->_publish_status = $value;
        endif;
        return $this->_publish_status;
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_model_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**    
     * @return string
     *  
     */
    public function get_model_class () {
        return get_class($this);
    }
}

