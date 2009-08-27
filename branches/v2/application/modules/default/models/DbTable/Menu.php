<?

class Model_DbTable_Menu
extends Zupal_Table_Abstract
{
    protected $_name = 'zupal_menus';

    const CREATE_SQL = '
CREATE TABLE `zupal_menus` (
  `id` int(11) NOT NULL auto_increment,
  `parent` varchar(45) collate utf8_bin NOT NULL,
  `module` varchar(45) collate utf8_bin NOT NULL,
  `controller` varchar(45) collate utf8_bin NOT NULL,
  `action` varchar(45) collate utf8_bin NOT NULL,
  `href` varchar(255) collate utf8_bin NOT NULL,
  `callback_class` varchar(45) collate utf8_bin NOT NULL,
  `parameters` varchar(255) collate utf8_bin NOT NULL,
  `ifmodule` tinyint(4) NOT NULL,
  `ifcontroller` varchar(0) collate utf8_bin NOT NULL,
  `sort_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;';
    public function create_table()
    {
        $this->getDefaultAdapter()->query(self::CREATE_SQL);
    }

}