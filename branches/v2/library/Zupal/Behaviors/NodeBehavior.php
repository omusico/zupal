<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Zupal_Behaviors_NodeBehavior extends Doctrine_Record_Generator
{
	public function initOptions()
    {
        $this->setOption('className', '%CLASS%Node');
    }

    public function buildRelation()
    {
        $this->buildForeignRelation('Node');
        $this->buildLocalRelation();
    }

    public function setTableDefinition()
    {
        $this->hasColumn('nid', 'int', 8, array(
                'node'  => true,
                'primary' => true
            )
        );
    }
}

