<?php

class Model_Zupalatoms extends Zupal_Domain_Abstract
implements  Model_ZupalatomIF {

    private static $_instance = 'zupal_atoms';

    public function tableClass() {
        return 'Model_DbTable_Zupalatoms';
    }

    public function get($pID = 'NULL', $pLoad_Fields = 'NULL') {
        $out = new self($pID);
        if ($pLoad_Fields && is_array($pLoad_Fields)):
            $out->set_fields($pLoad_Fields);
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ title @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_title() {
        return $this->title;
    }

    public function set_title($pValue) {
        $this->title = $pValue;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ lead @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_lead() { return $this->lead; }

    public function set_lead($pValue) { $this->lead = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ body @@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_body() { return $this->body; }

    public function set_body($pValue) { $this->body = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_atomic_id @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_atomic_id () { return $this->atomic_id; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_model_class @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function get_model_class () {
        return $this->model_class;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_atom @@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    public function get_atom ($pAtomic_id, $pVersion = NULL) {
        if (is_null($pVersion)):
            return Model_Zupalatoms::latest($pAtom_id);
        else:
            return $this->find(
            array('atomic_id' => $pAtomic_id,
            'version' => $pVersion)
            );
    endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_bonds @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param variant $pType = NULL
     * @return Model_Zupalbonds[]
     */
    public function get_bonds ($pType = NULL,
        Model_ZupalatomIF $pTarget = NULL,
        Model_ZupalatomIF $bond_atom = NULL
        ){


        $params = array(
            'from_atom' => $this->get_atomic_id(),
            'from_model_class' => $this->model_class
        );

        if (!is_null($pTarget)):
            $params['target'] = $pTarget;
        endif;

        if (!is_null($bond_atom)):
            $params['bond_atom'] = $bond_atom->get_atomic_id();
        endif;

        return Model_Zupalbonds::getInstance()->find($params, 'rank');
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ bond @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pTarget
     * @return <type>
     */
    public function bond ($pTarget) {
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ bond @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param string $pType, $pSingular = TRUE, $pTarget
     * @return Model_Zupalbonds
     */
    public function bond_to ($pType,
        Model_ZupalatomIF $pTarget,
        $pSingular = TRUE,
        Model_ZupalatomIF $bond_atom = NULL) {

        $params = array(
            'from_atom' => $this->get_atomic_id(),
            'from_model_class' => $this->model_class,
            'type' => $pType,
            'to_atom' => $pTarget->get_atomic_id()
        );

        if ($bond_atom):
            $params['bond_atom'] = $bond_atom->get_atomic_id();
        endif;

        if ($pSingular):
            if ($found = Model_Zupalbonds::getInstance()->findOne($params)):
                return $found;
        endif;
        endif;

        $bond = Model_Zupalbonds::getInstance()->get(NULL, $params);
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

        $params = array(
            'from_atom' => $this->get_atomic_id(),
            'from_model_class' => $this->get_model_class(),
            'type' => $pType,
            'to_atom' => $pTarget->get_atomic_id()
        );

        if ($bond_atom):
            $params['bond_atom'] = $bond_atom->get_atomic_id();
        endif;

        if ($found = Model_Zupalbonds::getInstance()->find($params)):
            foreach($found as $bond):
                $bond->delete();
            endforeach;
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ latest @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * NOTE: while this could be accomplihed with get_from_sql, this method
     * is ripe for memcache and so its good to keep unique
     *
     * @param string $pClass
     * @param int $pAtom_id
     * @return Model_Zupalatoms
     */

    const LATEST_SQL = 'SELECT * FROM %s WHERE atomic_id = ? ORDER BY version DESC LIMIT 1';

    public static function latest ($pAtom_id, $pAs_Array = FALSE) {
        $at = self::getInstance();

        if (!defined('ZUPALATOMS_GET_LATEST_SQL')):
            define ('ZUPALATOMS_GET_LATEST_SQL',
                sprintf(self::LATEST_SQL, self::getInstance()->table()->tableName()));
        endif;

        $row = $at->table()->getAdapter()->fetchRow(
            ZUPALATOMS_GET_LATEST_SQL, $pAtom_id);
        if ($row):
            if ($pAs_Array):
                return $row;
            endif;
            $id_field = $at->table()->idField();
            $id = $row[$id_field];
            unset($row[$id_field]);
            return $at->get($id, $row);
        else:
            return NULL;
    endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private static $_Instance = NULL;
    /**
     *
     * @param string $pReload
     * @return Model_Zupalatoms
     */
    public static function getInstance($pReload = FALSE) {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_new @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return Model_Zupalatoms
     */
    public static function get_new () {
        $za = new self();
        $za->save();
        $za->atomic_id = $za->identity();
        $za->version = 1;
        $za->save();
        return $za;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ revise @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param  array $pParams
     * @return Model_Zupalatoms
     */
    public function revise (array $pParams) {
        $change = FALSE;
        $pParams['atomic_id'] = $this->atomic_id;

        foreach($params as $f => $v):
            if ($this->$f != $v):
                $change = TRUE;
        endif;
        endforeach;

        if ($change):
            $pParams = array_merge($this->toArray(), $pParams);
            unset($pParams[$this->table()->idField()]);
            $pParams['version'] = $this->version + 1;
            return $this->get(NULL, $pParams);
        endif;
    }

}