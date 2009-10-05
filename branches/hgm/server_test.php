<?

include 'server/init.php';

$server = new Xtractlib_Server();

print_r($server->addurl($_REQUEST['url'] ? $_REQUEST['url'] : 'http://piliq.com/javafx/?p=1108'));