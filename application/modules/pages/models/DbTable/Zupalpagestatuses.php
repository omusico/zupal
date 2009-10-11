<?php

class Pages_Model_DbTable_Zupalpagestatuses extends Zupal_Table_Abstract
{

    protected $_name = 'zupal_pagestatuses';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ create_table @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @return void
     */
    public function create_table () {
        $sql = <<<SQL_BLOCK
CREATE TABLE `zupal_pagestatuses` (
  `status` varchar(45) collate utf8_bin NOT NULL,
  `title` varchar(200) collate utf8_bin NOT NULL,
  `rank` tinyint(4) NOT NULL,
  PRIMARY KEY  (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `zupal_pagestatuses` VALUES('published', 'Published', 100);
INSERT INTO `zupal_pagestatuses` VALUES('approved', 'Approved', 50);
INSERT INTO `zupal_pagestatuses` VALUES('rejected', 'Rejected', -10);
INSERT INTO `zupal_pagestatuses` VALUES('flagged', 'Flagged', 0);
INSERT INTO `zupal_pagestatuses` VALUES('created', 'Created', 2);
INSERT INTO `zupal_pagestatuses` VALUES('updated', 'Updated', 3);
INSERT INTO `zupal_pagestatuses` VALUES('contributed', 'Contributed', 1);

SQL_BLOCK;
        $this->getAdapter()->query($sql);
    }
}

