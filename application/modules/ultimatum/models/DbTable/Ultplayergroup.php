<?php

class Ultimatum_Model_DbTable_Ultplayergroup extends Zupal_Table_Abstract
{

    protected $_name = 'ult_player_group';

    public function create_table()
    {
        $sql = <<<SQL
        
        SQL;
        $this->getAdapter()->query($sql);;
    }


}

