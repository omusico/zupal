<?php

class Model_Zupalbonds extends Zupal_Domain_Abstract
{

    public function tableClass()
    {
        return 'Model_DbTable_Zupalbonds';
    }

    public function get($pID = NULL, $pLoadFields = NULL)
    {
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
     *
     * @param array $pData
     * @return variant
     */
    public function _bond_as ($pData, $pResultType = 'record') {
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
        $t = $this->from_model_class;
        $m = new $t(Zupal_Domain_Abstract::STUB);
        return $m->for_atom_id($this->from_atom);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ to_atom @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return Model_ZupalatomIF
     */
    public function to_atom () {
        $t = $this->to_model_class;
        $m = new $t(Zupal_Domain_Abstract::STUB);
        return $m->for_atom_id($this->to_atom);
    }

}