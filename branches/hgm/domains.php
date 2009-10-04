<?

include 'server/init.php';

$table = Xtract_Model_UrlDomains::getInstance()->table();

$domains = $table->getAdapter()->fetchCol('SELECT host FROM ' . $table->tableName());

if (array_key_exists('form', $_REQUEST)):
    switch(strtolower($_REQUEST['form'])):
        case 'json':
            echo Zend_Json::encode($domains);
        break;

        case 'processing':
            echo join(',', $domains);
        break;
    endswitch;
else:
    echo Zend_Json::encode($domains);
endif;