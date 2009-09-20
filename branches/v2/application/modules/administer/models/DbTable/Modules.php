<?

class Administer_Model_DbTable_Modules
extends Zupal_Table_Abstract
{
    protected $_name = 'zupal_modules';

    protected $_id_field = 'folder';
    
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    const CREATE_SCRIPT = "CREATE TABLE `zupal_modules` (
  `folder` varchar(45) collate utf8_bin NOT NULL,
  `sort_by` tinyint(3) unsigned NOT NULL,
  `title` varchar(100) collate utf8_bin NOT NULL,
  `notes` text collate utf8_bin NOT NULL,
  `version` varchar(45) collate utf8_bin NOT NULL,
  `required` tinyint(4) NOT NULL default '0',
  `package` varchar(50) collate utf8_bin NOT NULL,
  `active` tinyint(3) unsigned NOT NULL default '0',
  `menu_loaded` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`folder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
    
/**
 *
 * @return void
 */
    public function create_table () {
        $this->getAdapter()->query(self::CREATE_SCRIPT);
    }
    
}