<?php

class Model_Zupalatoms extends Zupal_Domain_Abstract
{

    private static $_instance = 'zupal_atoms';

    public function tableClass()
    {
        return 'Model_DbTable_Zupalatoms';
    }

    public function get($pID = 'NULL', $pLoad_Fields = 'NULL')
    {
        $out = new self($pID);
            if ($pLoad_Fields && is_array($pLoad_Fields)):
                $out->set_fields($pLoad_Fields);
            endif;
            return $out;
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
            define ('ZUPALATOMS_GET_LATEST_SQL',  sprintf(self::LATEST_SQL, self::getInstance()->table()->tableName()));
        endif;

        $row = $at->table()->getAdapter()->fetchRow(ZUPALATOMS_GET_LATEST_SQL, $pAtom_id);
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
}

