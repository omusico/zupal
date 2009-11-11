<?php

class Ultimatum_Model_Ultgames extends Zupal_Domain_Abstract
{

    private static $_Instance;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultgames';
    }

/**
 *
 * @return Ultimatum_Model_Ultgames
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
 * @return Ultimatum_Model_Ultgames
 */
    public function get($pID = NULL, $pLoadFields = NULL)
    {
        $out = new self($pID);
            if ($pLoadFields && is_array($pLoadFields)):
                $out->set_fields($pLoadFields);
            endif;
            return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ user_active_game @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return Ultimatum_Model_Ultgames
     */
    public static function user_active_game ($pUser = NULL) {
        $player = Ultimatum_Model_Ultplayers::user_active_player($pUser);
        if (!$player):
            return NULL;
        else:
            return $player->get_game();
        endif;
    }

   /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ players @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param boolean $pIDs
     * @return
     */
    public function players ($pIDs) {
        $pi = Ultimatum_Model_Ultplayers::getInstance();
        if ($pIDs):
            $columns = array('id');
            $select = $pi->table()->select()
                ->from($pi->table()->tableName(), $columns)
                ->where('game = ?', $this->identity());
                $sql = $select->assemble();
                
            return $pi->table()->getAdapter()->fetchCol($sql);
        else:
            return $pi->find(array('game' => $this->identity()));
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ title @@@@@@@@@@@@@@@@@@@@@@@@ */

    /**
     * @return string;
     */

    public function get_title() { return $this->title ? $this->title : 'Ultimatum Game ' . $this->identity(); }

    public function set_title($pValue) { $this->title = $pValue; $this->save();}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ turn @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return int
     */
    public function turn ($pAs_int = TRUE) {
        $turn = Ultimatum_Model_Ultgameturns::getInstance()->last_turn($this);
        return $pAs_int ? $turn->turn : $turn;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_player @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Model_User $pUser
     * @return Ultimatum_Model_Ultplayer
     */
    public function add_user ($pUser) {
        $player = Ultimatum_Model_Ultplayers::for_user_game($pUser, $this);
        $player->activate();
        return $player;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ delete @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param boolean $pErase
     * @return void
     */
    public function delete ($pErase = FALSE) {

        foreach($this->players() as $player):
            $player->delete($pErase);
        endforeach;

        if ($pErase):
            return parent::delete();
        endif;

        $this->status = 'deleted';
        $this->save();
    }
}
