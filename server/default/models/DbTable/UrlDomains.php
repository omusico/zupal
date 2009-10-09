<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Urls
 *
 * @author bingomanatee
 */

require_once('Xtractlib/Table/Abstract.php');

class Xtract_Model_DbTable_UrlDomains
extends Xtractlib_Table_Abstract
{
	protected $_id_field = 'id'; // note this standard convention is NOT automatically overrriden by table definition.
	protected $_name = 'url_domains';

        public function create_table (){
            $this->getAdapter()->query(self::CREATE);
        }

        const CREATE = "DROP TABLE IF EXISTS `url_domains`;
CREATE TABLE `url_domains` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `host` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;";
}