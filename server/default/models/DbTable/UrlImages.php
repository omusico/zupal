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

class Xtract_Model_DbTable_UrlImages
extends Xtractlib_Table_Abstract
{
	protected $_id_field = 'id'; // note this standard convention is NOT automatically overrriden by table definition.
	protected $_name = 'url_images';

        public function create_table (){
            $this->getAdapter()->query(self::CREATE);
        }

        const CREATE = "CREATE TABLE `url_images` (
  `id` int(11) NOT NULL auto_increment,
  `href` varchar(255) NOT NULL,
  `in_url` int(11) NOT NULL,
  `href_url` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;";
}