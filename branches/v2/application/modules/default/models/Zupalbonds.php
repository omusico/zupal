<?php

class Model_Zupalbonds extends Zupal_Domain_Abstract {

    public function tableClass() {
        return 'Model_DbTable_Zupalbonds';
    }

    public function get($pID = NULL, $pLoadFields = NULL) {
        $out = new self($pID);
        if ($pLoadFields && is_array($pLoadFields)):
            $out->set_fields($pLoadFields);
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Model_Zupalbonds
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_bonds_to @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param int | Model_Zupalatoms $pAtom
     * @return Model_Zupalbonds[]
     */
    public function get_bonds_to ($pAtom, $pType = NULL, $pResultType = 'record') {
        if ($pAtom instanceof Model_ZupalatomIF):
            $pAtom = $pAtom->get_atomic_id();
        endif;

        if (!is_numeric($pAtom)):
            throw new Exception(__METHOD__ . ': bad id passed: ' . print_r($pAtom, 1));
        endif;

        $params = array('to_atom' => $pAtom);
        if ($pType):
            $params['type'] = $pType;
        endif;

        return $this->_bond_as($this->find($params, 'rank'), $pResultType);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_bonds_to @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param int | Model_Zupalatoms $pAtom
     * @return Model_Zupalbonds[]
     */
    public function get_bonds_from ($pAtom, $pType = NULL, $pResultType = 'record') {
        if ($pAtom instanceof Model_ZupalatomIF):
            $pAtom = $pAtom->get_atomic_id();
        endif;

        if (!is_numeric($pAtom)):
            throw new Exception(__METHOD__ . ': bad id passed: ' . print_r($pAtom, 1));
        endif;

        $params = array('from_atom_id' => $pAtom);
        if ($pType):
            $params['type'] = $pType;
        endif;

        return $this->_bond_as($this->find($params, 'rank'), $pResultType . '_from');
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _bond_as @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * what the heck was I thinking? have no idea what this does. or why.
     * @param array $pData
     * @return variant
     */
    public function set_bond_as ($pData, $pResultType = 'record') {
        switch ($pResultType):
            case 'from_atom':
            case 'atom_from':
                $out = array();
                foreach($pData as $d):
                    $out[] = $d->from_atom();
                endforeach;
                break;

            case 'to_atom':
            case 'atom_to':
                $out = array();
                foreach($pData as $d):
                    $out[] = $d->to_atom();
                endforeach;
                break;
            case 'record':
            default:
                $out = $pData;
                break;
        endswitch;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ from_atom @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return Model_ZupalatomIF
     */
    public function from_atom () {
        if (!$this->from_atom):
            return NULL;
        endif;

        $model_class = $this->from_model_class;
        if (!$model_class):
            $atom = Model_Zupalatoms::getInstance()->for_atom_id($this->from_atom);
            $this->from_model_class = $model_class = $atom->get_model_class();

            if ($this->isSaved()):
                $this->save();
            endif;
        endif;

        if ($model_class):
            $m = new $model_class(Zupal_Domain_Abstract::STUB);
            return $m->for_atom_id($this->from_atom);
        else:
            return Model_Zupalatoms::getInstance()->for_atom_id($this->from_atom);
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_from @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Model_ZupalatomIF || int $pAtom
     */
    public function set_from_atom ($pAtom) {
        if (!$pAtom instanceof Model_ZupalatomIF):
            if (is_numeric($pAtom)):
                $pAtom = Model_Zupalatoms::getInstance()->get_atom($pAtom);
        endif;
        endif;

        $this->from_atom = $pAtom->get_atomic_id();
        $this->from_model_class = $pAtom->get_model_class();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ to_atom @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return Model_ZupalatomIF
     */
    public function to_atom() {
        if (!$this->to_atom):
            return NULL;
        endif;

        $model_class = $this->to_model_class;
        if (!$model_class):
            $atom = Model_Zupalatoms::getInstance()->for_atom_id($this->to_atom);
            $this->to_model_class = $model_class = $atom->get_model_class();
            if ($this->isSaved()):
                $this->save();
        endif;
        endif;

        if ($model_class):
            $m = new $model_class(Zupal_Domain_Abstract::STUB);
            return $m->for_atom_id($this->to_atom);
        else:
            return Model_Zupalatoms::getInstance()->for_atom_id($this->to_atom);
    endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_from @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Model_ZupalatomIF || int $pAtom
     */
    public function set_to_atom (Model_ZupalatomIF $pAtom) {
        if (!$pAtom instanceof Model_ZupalatomIF):
            if (is_numeric($pAtom)):
                $pAtom = Model_Zupalatoms::getInstance()->get_atom($pAtom);
        endif;
        endif;

        if ($pAtom):

            $this->to_atom = $pAtom->get_atomic_id();
            $this->to_model_class = $pAtom->get_model_class();
        else:
            $this->to_atom = 0;
            $this->to_model_class = '';
        endif;

        $this->save();
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ bond_atom_rcord @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * returns the record encasing a given bond.
     * NOTE: despite the method name, it returns the class encasing the atom,
     * not the Model_Zupalatoms record itself,
     * if a model_class is present.
     *
     * @return Model_ZupalatomIF
     */
    public function bond_atom () {
        if (!$this->bond_atom):
            return NULL;
        endif;

        $model_class = $this->bond_model_class;
        if (!$model_class):
            $atom = Model_Zupalatoms::getInstance()->for_atom_id($this->bond_atom);
            $this->bond_model_class = $model_class = $atom->get_model_class();
            if ($this->isSaved()):
                $this->save();
        endif;
        endif;

        if ($model_class):
            $m = new $model_class(Zupal_Domain_Abstract::STUB);
            return $m->for_atom_id($this->bond_atom);
        else:
            return Model_Zupalatoms::getInstance()->for_atom_id($this->bond_atom);
    endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ set_from @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Model_ZupalatomIF || int $pAtom
     */
    public function set_bond_atom (Model_ZupalatomIF $pAtom) {
        if ($pAtom):
            if (!$pAtom instanceof Model_ZupalatomIF):
                if (is_numeric($pAtom)):
                    $pAtom = Model_Zupalatoms::getInstance()->get_atom($pAtom);
            endif;
            endif;

            $this->bond_atom = $pAtom->get_atomic_id();
            $this->bond_model_class = $pAtom->get_model_class();
        else:
            $this->bond_atom = 0;
            $this->bond_model_class = '';
    endif;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _prep_params @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param array $pParams
     * @return array
     */
    public function _prep_params (array $pParams) {
        foreach(array('from_atom', 'to_atom', 'bond_atom') as $key):
            if ((array_key_exists($key, $pParams))
                && (is_object($pParams[$key]))):
               $ob = $pParams[$key];
               $pParams[$key] = $ob->get_atomic_id();
            endif;
        endforeach;
        return $pParams;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ make_unique_bond @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * $pForce_new, if false, attempts to reuse any existing bond that qualifies.
     *      if true, it scrubs ALL old bonds and makes a new one.
     * @param array $pParams
     * @return <type>
     */

     
    public static function make_unique_bond (array $pParams, $pForce_new = TRUE) {
        $pParams = self::_prep_params($pParams);
        $old_bonds = self::getInstance()->find($pParams, 'id');
        $new_bond = FALSE;

        if (count($old_bonds)):
            if(!$pForce_new):
                $new_bond = array_pop($old_bonds);
                foreach($old_bonds as $old_bond):
                    $old_bond->delete();
                endforeach;
            else:
                foreach($old_bonds as $old_bond):
                    $old_bond->delete();
                endforeach;
            endif;
        endif;

        if (!$new_bond):
            $new_bond = self::getInstance()->get(NULL, $pParams);
            $new_bond->find_models(FALSE);
            $new_bond->save();
        endif;

        return $new_bond;
    }

    public function find_models ($pSave = TRUE) {
        $a = $this->to_atom();
        $a_class = get_class($a);
        $this->to_model_class = $mc = ($a) ? $a->get_model_class() : '';

        $a = $this->from_atom();
        $a_class = get_class($a);
        $this->from_model_class = $mc =  ($a) ? $a->get_model_class() : '';

        $a = $this->bond_atom();
        $a_class = get_class($a);
        $this->bond_model_class = $mc = ($a) ? $a->get_model_class() : '';
        
        if ($pSave): 
            $this->save();
        endif;
    }

}