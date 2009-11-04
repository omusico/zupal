<?php

class Ultimatum_Model_DbTable_Ultgameturns extends Zupal_Table_Abstract
{

    protected $_name = 'ult_game_turns';

    public function create_table()
    {
        $sql = <<<SQLDATA
         CREATE TABLE `zupal2`.`ult_game_turns` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`game` INT NOT NULL ,
`turn` INT NOT NULL ,
`notes` TEXT NOT NULL
) ENGINE = MYISAM
SQLDATA;
        $this->getAdapter()->query($sql);
    }


}

