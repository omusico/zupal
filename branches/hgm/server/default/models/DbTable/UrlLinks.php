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

class Xtract_Model_DbTable_UrlLinks
extends Xtractlib_Table_Abstract
{
    protected $_id_field = 'id'; // note this standard convention is NOT automatically overrriden by table definition.
    protected $_name     = 'url_links';

    public function create_table (){
        $this->getAdapter()->query(self::CREATE);
    }

    const CREATE = "CREATE TABLE `url_links` (
  `id` int(11) NOT NULL auto_increment,
  `from_url` int(11) NOT NULL,
  `to_url` int(11) NOT NULL,
  `found_in_html` int(11) NOT NULL,
  `linked` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=487 ;";
}