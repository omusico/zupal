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

class Xtract_Model_DbTable_UrlHtmls
extends Xtractlib_Table_Abstract
{
	protected $_id_field = 'id'; // note this standard convention is NOT automatically overrriden by table definition.
	protected $_name = 'url_htmls';

        public function create_table (){
            $this->getAdapter()->query(self::CREATE);
        }

        const CREATE = "DROP TABLE IF EXISTS `url_htmls`;
CREATE TABLE `url_htmls` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `in_url` int(11) NOT NULL,
  `html` text NOT NULL,
  `scanned_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;";
}