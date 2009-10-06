<?

include 'server/init.php';

foreach(array('urls', 'url_domains', 'url_htmls','url_images', 'url_links') as $table):
    Zend_Db_Table::getDefaultAdapter()->query("Drop Table $table");

endforeach;