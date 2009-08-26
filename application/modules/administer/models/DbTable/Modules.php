<?

class Administer_Model_DbTable_Modules
extends Zupal_Table_Abstract
{
    protected $_name = 'zupal_modules';
    
    /* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    const CREATE_SCRIPT = " CREATE TABLE `zupal2`.`zupal_modules` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`folder` VARCHAR( 45 ) NOT NULL ,
`title` VARCHAR( 100 ) NOT NULL ,
`status` SET( 'active', 'missing' ) NOT NULL ,
`notes` TEXT NOT NULL ,
`version` VARCHAR( 45 ) NOT NULL
) ENGINE = MYISAM ";
    
/**
 *
 * @return void
 */
    public function create_table () {
        $this->getAdapter()->query(self::CREATE_SCRIPT);
    }
    
}