<?php

class Game_Model_Gamesessions extends Zupal_Domain_Abstract {

    private static $_Instance = null;

    protected $_soft_delete = TRUE;

    public function tableClass() {
        return 'Game_Model_DbTable_Gamesessions';
    }

    public static function getInstance() {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

    public function get($pID = NULL, $pLoadFields = NULL) {
        $out = new self($pID);
        if ($pLoadFields && is_array($pLoadFields)):
            $out->set_fields($pLoadFields);
        endif;
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ jsonArray @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * @return array
     */
    public function toArray ($pExtend = TRUE) {
        $out = parent::toArray();
        if ($pExtend):
            $out['game'] = $this->game_type()->title;
        endif;
        $out['plsyers'] = $this->player_count();
        return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ game_type @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_game_type = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Game_Model_Gametypes
     */
    function game_type($pReload = FALSE) {
        if ($pReload || is_null($this->_game_type)):
        // process
            $this->_game_type = Game_Model_Gametypes::getInstance()->get($this->game_type);
        endif;
        return $this->_game_type;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ player_count @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return int
     */
    public function player_count () {
        $session_player_table = Game_Model_Gamesessionplayers::getInstance()->table();
        $sql = sprintf("SELECT count(ID) FROM %s WHERE user = ?", $session_player_table->tableName());
        return $session_player_table->getAdapter()->fetchOne($sql, array($this->user));
    }

    const ACTIVE_KEY = 'active';
    
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ active_for_player @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param int | Model_users $pUser = NULL
     */
    public function add_user ($pUser = NULL) {
        if($pUser):
            $pUser = Zupal_Domain_Abstract::_as($pUser, 'Model_Users');
        else:
            $pUser = Model_Users::current_user();
        endif;

        if (!$pUser):
            throw new Exception(__METHOD__ . ': user missing');
        endif;

        return Model_Zupalbonds::getInstance()->make_unique_bond(
            array(
                'bond_atom' => $this->game_type(),
                'from_atom' => $pUser,
                'type' => self::ACTIVE_KEY
            )
        );
    }


        public function activate ($pFor_user = NULL) {
        $bond = new Model_Zupalbonds();
        if (!$pFor_user):
            $pFor_user = Model_Users::current_user();
            if (!$pFor_user):
                throw new Exception(__METHOD__ . ': trying to bond missing user to session');
            endif;
        endif;

        $bond->type = 'active';
        $bond->set_to_atom($this);
        $bond->set_from_atom($pFor_user);
        $bond->set_bond_atom($this->game_type());

        $params = array(
            'from_atom' => $bond->from_atom()->get_atomic_id(),
            'bond_atom' => $bond->to_atom()->get_atomic_id(),
            'type' => 'active'
            );

       $other_bonds = $bond->find($params);

       $other_bond = array_pop($other_bonds);

       if (count($other_bonds)):
            foreach($other_bonds as $extra_bond):
                $extra_bond->delete();
            endforeach;
       endif;

       // find an existing bond that is a syner-G game activation for this user.

       if ($other_bond):
            $other_bond->set_to_atom($this);
            $other_bond->save();
       else:
            $bond->save();
       endif;
    }
}

