<?php

class Ultimatum_Model_DbTable_Ultplayergroupknowledge extends Zupal_Table_Abstract
{

    protected $_name = 'ult_player_group_knowledge';

    public function create_table()
    {
        $sql = <<<SQL
        
        SQL;
        $this->getAdapter()->query($sql);;
    }


}

