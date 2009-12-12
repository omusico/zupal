<?php

class Game_Model_Gamesessions extends Model_Zupalatomdomain {

    private static $_Instance = null;

    protected $_soft_delete = TRUE;

    public function tableClass() {
        return 'Game_Model_DbTable_Gamesessions';
    }

    /**
     *
     * @return Game_Model_Gamesessions
     */
    public static function getInstance() {
        if ($pReload || is_null(self::$_Instance)):
        // process
            self::$_Instance = new self();
        endif;
        return self::$_Instance;
    }

    /**
     *
     * @param int $pID
     * @param array $pLoadFields
     * @return  Game_Model_Gamesessions
     */
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
        $out['players'] = $this->player_count();
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

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ is_game_type @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pGame_type
     * @return <type>
     */
    public function is_game_type ($pGame_type) {
        $pGame_type = Zupal_Domain_Abstract::_as($pGame_type, 'Game_Model_Gametypes', TRUE);
        return $this->game_type == $pGame_type ? TRUE : FALSE;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ player_count @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return int
     */
    public function player_count () {
        $session_player_table = Game_Model_Gamesessionplayers::getInstance()->table();
        $sql = sprintf("SELECT count(ID) FROM %s WHERE game_session = ?", $session_player_table->tableName());
        return $session_player_table->getAdapter()->fetchOne($sql, array($this->identity()));
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ active_session @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * This method ASSUMES there is a unique record that satisfies the criteria --
     * only one active game sesion of a given type per user. This should be enforced
     * by other mechanisms.
     *
     * In any case whatever is returned by this method will be activated, enforcing subsequent references
     * back to the same record.
     *
     * @param  $pGame_type
     * @param  $pUser
     * @return Game_Model_Gamesessions
     */
    public function active_session ($pGame_type, $pUser = NULL) {

        $game_type_id = Zupal_Domain_Abstract::_as($pGame_type, 'Game_Model_Gametypes', TRUE);

        if ($pUser):
            $user_id = Zupal_Domain_Abstract::_as($pUser, 'Model_Users', TRUE);
        else:
            $user = Model_Users::current_user();
            if (!$user):
                throw new Exception(__METHOD__ . ': no current user');
            endif;
            $user_id = $user->identity();
        endif;

        $params = array('game_type' => $game_type_id, 'user' => $user_id, 'active' => 1);

        $active_player_session = Game_Model_Gamesessionplayers::getInstance()->findOne($params, 'ID desc');
        $active_session = $active_player_session->session();

        $active_session->activate();

        return $active_session;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ active_for_player @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    const ACTIVE_KEY = 'active';
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

        $active_bond = Model_Zupalbonds::getInstance()->make_unique_bond(
            array(
            'bond_atom' => $this->game_type(),
            'from_atom' => $pUser,
            'type' => self::ACTIVE_KEY
            )
        );
        $active_bond->set_to_atom($this);
        return $active_bond;
    }


    public function activate ($pFor_user = NULL) {

        $game_type = $this->game_type();
        $gtid = $game_type->identity();

        if ($pFor_user):
            $uid = Zupal_Domain_Abstract::_as($pFor_user, 'Model_Users', TRUE);
        elseif ($user = Model_Users::current_user()):
            $uid = $user->identity();
        else:
            throw new Exception (__METHOD__ . ': no user present' );
        endif;

        $params = array(
            'game_type' => $gtid,
            'user' => $uid
        );

        $other_player_sessions = Game_Model_Gamesessionplayers::getInstance()->find($params);

        foreach($other_player_sessions as $other_player_session):
            if ($other_player_session->game_session == $this->identity()):
                $other_player_session->active = 1;
            else:
                $other_player_session->active = 0;
            endif;
            $other_player_session->save();
        endforeach;
        

            /*

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

             */
    }
}

