<?php

class Ultimatum_Model_Ultgameturns extends Zupal_Domain_Abstract
{

    private static $_Instance = null;

    public function tableClass()
    {
        return 'Ultimatum_Model_DbTable_Ultgameturns';
    }
/**
 *
 * @return Ultimatum_Model_Ultgameturns
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
 * @return Ultimatum_Model_Ultgameturns
 */
    public function get($pID = NULL, $pLoadFields = NULL)
    {
        $out = new self($pID);
            if ($pLoadFields && is_array($pLoadFields)):
                $out->set_fields($pLoadFields);
            endif;
            return $out;
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ last_turn @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param <type> $pGame
     * @return <type>
     */
    public function last_turn ($pGame) {
        if (!is_numeric($pGame)):
            if (!$pGame instanceof Ultimatum_Model_Ultgames):
                throw new Exception(__METHOD__ . ': bad value passed');
            endif;

            $pGame = $pGame->identity();
        endif;

        $params = array('game' => $pGame);
        $turn = $this->findOne($params, 'turn DESC');
        if (!$turn):
            $params['turn'] = 1;
            $params['notes'] = 'game start';

            $turn = $this->get(NULL, $params);
            $turn->save();
        endif;
        return $turn;
    }
}

