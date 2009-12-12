<?php

class Game_Model_Gameresourceclasses
extends Model_Zupalatomdomain
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     * an extension point for new records
     */
    public function init () {
        $this->_soft_delete = TRUE;
    }

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Game_Model_DbTable_Gameresourceclasses';
    }

    public static function getInstance()
    {
        if ($pReload || is_null(self::$_Instance)):
            // process
                self::$_Instance = new self();
            endif;
            return self::$_Instance;
    }

    public function get($pID = NULL, $pLoadFields = NULL)
    {
        $out = new self($pID);
            if ($pLoadFields && is_array($pLoadFields)):
                $out->set_fields($pLoadFields);
            endif;
            return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ game_type @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

    private $_game_type = NULL;
    function game_type($pReload = FALSE) {
        if ($pReload || is_null($this->_game_type)):
        // process
            $this->_game_type = Game_Model_Gametypes::getInstance()->get($this->game_type);
        endif;
        return $this->_game_type;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ resources @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return array
     */
    public function resource_types () {
        $params = array(
            'resource_class' => $this->identity(),
            'active' => 1
        );
        return Game_Model_Gameresourcetypes::getInstance()->find($params);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ move @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 * @param $pMode
 */
    public function move ($pMode) {
        $params = array(
            'game_type' => $this->game_type,
            'active' => 1
        );
        $data = $this->find($params, 'rank');
        parent::move($this, $pMode, 'rank');
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ options @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 * @param int | Game_Model_Gametypes  $pGame_type
 * @return array
 */
    public function options ($pGame_type) {
        $pGame_type = Zupal_Domain_Abstract::_as($pGame_type, 'Game_Model_Gametypes', TRUE);
        $params = array('game_type' => $pGame_type);
        $classes = $this->find($params, 'rank');
        $out = array();

        foreach($classes as $class):
            $out[$class->identity()] = $class->title;
        endforeach;

        return $out;
    }
}

