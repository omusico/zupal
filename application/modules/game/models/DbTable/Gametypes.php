<?php

class Game_Model_DbTable_Gametypes extends Zupal_Table_Abstract
{

    protected $_name = 'game_types';

    public function create_table()
    {
        $sql = <<<SQL
        
        SQL;
        $this->getAdapter()->query($sql);;
    }


}

