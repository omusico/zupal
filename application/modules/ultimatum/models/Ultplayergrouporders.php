<?php

class Ultimatum_Model_Ultplayergrouporders extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultplayergrouporders';
    }
/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ getInstance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @return Ultimatum_Model_Ultplayergrouporders
 */
    public static function getInstance()
    {
        if ($pReload || is_null(self::$_Instance)):
            // process
                self::$_Instance = new self();
            endif;
            return self::$_Instance;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @return Ultimatum_Model_Ultplayergrouporders
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

        if (!$this->series):
            $this->_init_series();
        endif;

        parent::save();
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _init_series @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function _init_series () {
        $sql= sprintf('SELECT MAX(series) FROM %s ', $this->table()->tableName());
        $sql .= sprintf(' WHERE player_group = %s', $this->player_group);
        $sql .= ' AND ((status = "pending") OR (status = "executing"))';

        $max_series = (int) $this->table()->getAdapter()->fetchOne($sql);
        $this->series = $max_series + 1;
    }


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ order_type @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_order_type = NULL;
    /**
     *
     * @param boolean $pReload
     * @return Ultimatum_Model_Ultplayergroupordertypes
     */
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
        $player_group = $this->player_group();
        $start_turn = $this->start_turn();
        if ($player_group):
            return $this->order_type() . ' to group ' . $player_group . ' on turn ' . $start_turn;
        else:
            return $this->order_type() . ' on turn ' . $start_turn;
        endif;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ start_turn @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return int
     */
    public function start_turn () {
        $select = $this->table()->select()
            ->where('player_group = ?', $this->player_group)
            ->where('series < ?', $this->series)
            ->where('status=?', 'pending')
            ->orWhere('status = ?', 'executing');

        $precedents = $this->find($select, 'series');
        $start_turn = $this->player_group()->get_game()->turn();
        foreach($precedents as $pgo):
            if ($pgo->identity() != $this->identity()):
                $type = $this->order_type();
                $start_turn += $type->turns;
            endif;
        endforeach;
        return $start_turn;
    }

    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ cancel @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function cancel () {
        $this->active = 0;
        $this->status = 'cancelled';
        $game = Zend_Registry::get('ultimatum_game');
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ clear_orders @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param $pPlayer_group
     * @return void
     */
    public static function clear_orders ($pPlayer_group, $pTarget = NULL) {
        $pPlayer_group = Zupal_Domain_Abstract::_as($pPlayer_group, 'Ultimatum_Model_Ultplayergroups', TRUE);
        $params = array('player_group' => $pPlayer_group);
        if ($pTarget):
            $pTarget = Zupal_Domain_Abstract::_as($pTarget, 'Ultimatum_Model_Ultgroups', TRUE);
            if ($pTarget):
                $params['target'] = $pTarget;
            endif;
        endif;

        foreach(self::getInstance()->find($params) as $order):
            $order->cancel();
        endforeach;

    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ cancel_link @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return string
     */
    public function cancel_link () {
        ob_start();
        ?><a href="/ultimatum/game/cancelorder/order/<?= $this->identity() ?>" class="linkbutton">Cancel Order</a><?
        return ob_get_clean();
    }
}

