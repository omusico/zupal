<?
// note -- for_atomic_id is not implemented. 
abstract class Model_Zupalatomdomain
    extends Zupal_Domain_Abstract
    implements Model_ZupalatomIF
{
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@ title @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_title() {
       return $this->get_atom()->get_title();
    }

    public function set_title($pValue){
       $this->get_atom()->set_title($pValue);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ lead @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_lead() {
       return $this->get_atom()->get_lead();
    }

    public function set_lead($pValue){
       $this->get_atom()->set_lead($pValue);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ content @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_content() {
        return $this->get_atom()->get_content();
    }

    public function set_content($pValue){
       $this->get_atom()->set_content($pValue);
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ format_lead @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_format_lead(){ return $this->get_atom()->get_format_lead();}

    public function set_format_lead($pValue){$this->get_atom()->set_format_lead($pValue); }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ format_content @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_format_content(){ return $this->get_atom()->get_format_content();}

    public function set_format_content($pValue){$this->get_atom()->set_format_content($pValue); }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_model_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function get_model_class () {
        return $this->get_atom()->get_model_class();
    }

    public function set_model_class ($pValue) {
        return $this->get_atom()->set_model_class($pValue);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ status @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @return class;
     */

    public function get_status() { return $this->get_atom()->get_status(); }

    public function set_status($pValue) { $this->get_atom()->set_status($pValue); }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ author @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @return class;
     */

    public function get_author() { return $this->get_atom()->get_author(); }

    public function set_author($pValue) { $this->get_atom()->set_author($pValue); }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ atom @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 *
 * This method returns the atomic model that this class decorates. 
 */
    protected $_atom = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Model_Zupalatoms
     */
    function get_atom($pReload = FALSE) {
        if (!$this->get_atomic_id()):
            $this->_spawn_atom();
        elseif ($pReload || is_null($this->_atom)):
            $this->_atom = Model_Zupalatoms::getInstance()->get_atom($this->get_atomic_id());
            if (!$this->_atom):
                $this->_spawn_atom();
            endif;
        endif;
        return $this->_atom;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _spawn_atom @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function _spawn_atom () {
        $this->_atom = new Model_Zupalatoms();
        $this->_atom->set_model_class(get_class($this));
        $this->set_atomic_id($this->_atom->get_atomic_id());
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_bonds @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ /*/
    /**
     *
     * @param variant $pType = NULL
     * @return Model_Zupalbonds[]
     */
    public function get_bonds ($pType = NULL,
        Model_ZupalatomIF $pTarget = NULL,
        Model_ZupalatomIF $bond_atom = NULL) {
        return Model_Zupalbonds::getInstance()->get_bonds_from($this, $pType);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ bond @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

/**
 *
 * @param string $pType
 * @param Model_ZupalatomIF $pTarget
 * @param bool $pSingular
 * @param Model_ZupalatomIF $bond_atom
 * @return Model_Zupalbonds
 */
    public function bond_to ($pType,
        Model_ZupalatomIF $pTarget,
        $pSingular = TRUE,
        Model_ZupalatomIF $bond_atom = NULL) {
        //@TODO: enforce singularity

        $bond = new Model_Zupalbonds();
        $bond->to_atom = $pTarget->get_atomic_id();
        $bond->to_model_class = $pTarget->get_model_class();

        $bond->from_atom = $this->get_atomic_id();
        $bond->from_model_class = $this->get_model_class();

        $bond->type = $pType;
        if ($bond_atom):
            $bond->bond_atom = $bond_atom->get_atomic_id();
        endif;

        $bond->save();

        return $bond;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ unbond @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param id | Model_ZupalatomIF $pTarget
     * @return void
     */
    public function unbond ($pTarget, $pType = NULL,
        Model_ZupalatomIF $bond_atom = NULL) {
        throw new Exception(__METHOD__ . ' not implemented');
    }  

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */
    public function save () {
        if (($atom = $this->get_atom()) && $atom->isSaved()):
            try {
                $this->get_atom()->save();
            } catch (Exception $e) {
                error_log(__METHOD__ . ': error deleting atom ' . $e->getMessage());
            }
        endif;
        parent::save();
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ delete @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void;
     */
    public function delete () {
        try {
            $this->get_atom()->delete();
        }
        catch (Exception $e)
        {
            error_log(print_r($e, 1));
        }
        parent::delete();
    }
    
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_ion @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pKey
     * @param string $pValue = NULL
     * @return Model_Zupalions
     */
    public function add_ion ($name, $value = NULL) {
        if (is_array($name)):
            extract($name);
        endif;

        $params = array('name' => $name, 'value' => $value, 'atomic_id' => $this->get_atomic_id());

        if (!$ion = Model_Zupalions::getInstance()->findOne($params)):
            $ion = Model_Zupalions::getInstance()->get(NULL, $params);
            $ion->save();
        endif;
        return $ion;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_ion @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pName
     * @param boolean $pValue
     *     determines the return type -- domain or scalar.
     * @return Model_Zupalions
     */
    public function get_ion ($pName, $pValue = FALSE) {
        $params = array('atomic_id' => $this->get_atomic_id(), 'name' => $pName);
        $ion = Model_Zupalions::getInstance()->findOne($params);
        if ((!$ion) || (!$ion->isSaved())):
            return NULL;
        elseif ($pValue):
            return $ion->value;
        else:
            return $ion;
        endif;
    }

    public function __get($pField) {
        switch($pField):
            case 'title':
                return $this->get_title();
                break;

            case 'content':
                return $this->get_content();
                break;

            case 'lead':
                return $this->get_lead();
                break;


        default:
                return parent::__get($pField);
            endswitch;
    }

    public function  __set($pField,  $value) {
                switch($pField):
            case 'title':
                return $this->set_title($value);
                break;

            case 'content':
                return $this->set_content($value);
                break;

            case 'lead':
                return $this->set_lead($value);
                break;


        default:
                return parent::__set($pField, $value);
            endswitch;
    }


    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ toArray @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type>
     * @return <type>
     */
    public function toArray () {
        $out = parent::toArray();
        $out['title']   = $this->title;
        $out['lead']    = $this->lead;
        $out['content'] = $this->content;
        return $out;
    }

    public function __toString() {
        return $this->title;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@ atomic_id @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @return class;
     */

    public function get_atomic_id() { return $this->atomic_id; }

    public function set_atomic_id($pValue) { $this->atomic_id = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ for_atomic_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * NOTE: this works only if there is a 1:1 mapping betwen atomic_id fields and records.
     * if this is not true (i.e., the table is versioned) then hopefully the versions
     * are stored in the same order asn the identity field.
     * 
     * @param int $pAtomic_id
     * @return <type>
     */
    public function for_atom_id ($pAtomic_id) {
        $params = array('atomic_id' => $pAtomic_id);
        return $this->findOne($params, $this->table()->idField() . ' DESC');
    }
}