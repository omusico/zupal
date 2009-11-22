<?php

class Ultimatum_Model_Ultplayergrouporder extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergrouporder';
    }
/**
 *
 * @return Ultimatum_Model_Ultplayergrouporder
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
 * @return Ultimatum_Model_Ultplayergrouporder
 */
    public function get($pID = NULL, $pLoadFields = NULL)
    {
        $out = new self($pID);
            if ($pLoadFields && is_array($pLoadFields)):
                $out->set_fields($pLoadFields);
            endif;
            return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ player_group @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return Ultimatum_Model_Ultplayergroups
     */
    public function player_group () {
        return Ultimatum_Model_Ultplayergroups::getInstance()->get($this->player_group);
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ player @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return Ultimatum_Model_Ultplayers
     */
    public function player () {
        return $this->player_group()->player();
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function save () {
        if ($this->player_group && !$this->commander):
            $pg = $this->player_group();
            $this->commander = $pg->get_player()->identity();
        endif;
        return parent::save();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ order_type @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_order_type = NULL;
    function order_type($pReload = FALSE) {
        if ($pReload || is_null($this->_order_type)):
            $value = Ultimatum_Model_Ultplayergroupordertypes::getInstance()->get($this->type);
        // process
            $this->_order_type = $value;
        endif;
        return $this->_order_type;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ end_phrase @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * A prhase fitting the form "repeat (phrase)"
     *
     * @return string
     */
    public function end_phrase () {
        switch($this->repeat):

            case 'once':
                return 'once';
            break;

            case 'forever':
                return 'until given new orders';
            break;

            case 'turn':
                return 'until turn ' . $this->end_turn;
            break;

            case 'iterate':
                return $this->end_turn . ' times';
            break;

        endswitch;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ resize @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_resize = NULL;
    function resize($pReload = FALSE) {
        if ($pReload || is_null($this->_resize)):
            $params = array('order_id' => $this->identity());
            $value = Ultimatum_Model_Ultplayergrouporderresizes::getInstance()->findOne($params);
        // process
            $this->_resize = $value;
        endif;
        return $this->_resize;
    }

    public function __toString() {
        if ($player_group = $this->player_group()):
            return $this->order_type() . ' to group ' . $player_group . ' on ' . $this->start_turn;
        else:
            return $this->order_type() . ' on ' . $this->start_turn;
        endif;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ cancel @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function cancel () {
        $this->active = 0;
        $game = Zend_Registry::get('ultimatum_game');

        $this->interrupt_turn = $game->turn();
        $this->save();
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ target @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_target = NULL;
    function get_target($pReload = FALSE) {
        if ($pReload || is_null($this->_target)):
            if ($this->target):
                $value = Ultimatum_Model_Ultgroups::getInstance()->get($this->target);
            else:
                $value = FALSE;
            endif;
        // process
            $this->_target = $value;
        endif;
        return $this->_target;
    }

}

