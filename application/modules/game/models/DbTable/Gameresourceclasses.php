<?php

class Game_Model_DbTable_Gameresourceclasses extends Zupal_Table_Abstract
{

    protected $_name = 'game_resource_classes';

    public function create_table()
    {
        $sql = <<<SQL
 CREATE TABLE `zupal2`.`game_resource_classes` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`atomic_id` INT NOT NULL ,
`active` TINYINT NOT NULL ,
`game_type` INT NOT NULL
) ENGINE = MYISAM
SQL;
        $this->getAdapter()->query($sql);;
    }


}

