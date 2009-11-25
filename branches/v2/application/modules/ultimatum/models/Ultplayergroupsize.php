<?php

class Ultimatum_Model_Ultplayergroupsize extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergroupsize';
    }
/**
 *
 * @return Ultimatum_Model_Ultplayergroupsize
 */
    public static function getInstance()
    {
        if ($pReload || is_null(self::$_Instance)):
            // process
                self::$_Instance = new self();
            endif;
            return self::$_Instance;
    }

/**
 *
 * @return Ultimatum_Model_Ultplayergroupsize
 */
    public function get($pID = NULL, $pLoadFields = NULL)
    {
        $out = new self($pID);
        if ($pLoadFields && is_array($pLoadFields)):
            $out->set_fields($pLoadFields);
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ size_in_game @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param int | Ultimatum_Model_Ultgroups $pGroup_id
     * @param int | Ultimatum_Model_Ultgames | NULL $pGame_id
     * @return int
     */
    public function group_size ($pGroup_id, $pGame_id = NULL) {

       $pGroup_id = Zupal_Domain_Abstract::_as($pGroup_id, 'Ultimatum_Model_Ultgroups', TRUE);
       if (!$pGroup_id):
            throw new Exception(__METHOD__ . ': no group found');
       endif;

        if (is_null($pGame_id)):
            $pGame_id = Ultimatum_Model_Ultgames::get_active();
        else:
            $pGame_id = Zupal_Domain_Abstract::_as($pGame_id, 'Ultimatum_Model_Ultgames', TRUE);
        endif;

        if (!$pGame_id):
            throw new Exception(__METHOD__ . ': no game found');
        endif;

        $table =  $this->table();
        $sql = sprintf('select SUM(size) FROM %s WHERE group_id = ? AND game = ?',
           $table->tableName());
        $size = $table->getAdapter()->fetchOne($sql, array($pGroup_id, $pGame_id));
        return (int) $size;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ sizes_in_game @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    
    public function group_sizes ($pGroup, $pGame = NULL) {

       $pGroup = Zupal_Domain_Abstract::_as($pGroup, 'Ultimatum_Model_Ultgroups');
       if (!$pGroup):
            throw new Exception(__METHOD__ . ': no group found');
       endif;

        if (is_null($pGame)):
            $pGame = Ultimatum_Model_Ultgames::get_active();
        else:
            $pGame = Zupal_Domain_Abstract::_as($pGame, 'Ultimatum_Model_Ultgames');
        endif;

        if (!$pGame):
            throw new Exception(__METHOD__ . ': no game found');
        endif;

        $params = array('game' => $pGame->identity(), 'group_id' => $pGroup->identity());

        return $this->find($params, array('turn','changed_on'));

    }
    
}

