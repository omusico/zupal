<?php

class Model_DbTable_Zupalbonds extends Zupal_Table_Abstract
{

    protected $_name = 'zupal_bonds';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create_table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return <type>
     */

    public function create_table () {
        $sql = <<<CT_SQL
CREATE TABLE `zupal_bonds` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `from_atom` int(11) NOT NULL,
  `from_model_class` varchar(45) collate utf8_bin NOT NULL default 'Model_Zupalatoms',
  `to_atom` int(11) NOT NULL,
  `to_model_class` varchar(45) collate utf8_bin NOT NULL default 'Model_Zupalatoms',
  `bond_atom` int(11) default NULL,
  `bond_model_class` varchar(45) collate utf8_bin NOT NULL,
  `model_class` varchar(45) collate utf8_bin NOT NULL,
  `active` tinyint(4) NOT NULL,
  `creator` int(11) NOT NULL,
  `type` varchar(45) collate utf8_bin NOT NULL default 'parent',
  `bonded_on` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `updated_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  `rank` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;
CT_SQL;
        $this->table()->getAdapter()->query($sql);
    }
}

