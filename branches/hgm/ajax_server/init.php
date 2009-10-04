<?

require_once (dirname(__FILE__)) .  '/index.php';

$urls = new Xtract_Model_Urls(Xtractlib_Domain_Abstract::STUB);

echo $urls->table()->getAdapter()->fetchOne('SELECT COUNT(*) FROM ' . $urls->table()->tableName());