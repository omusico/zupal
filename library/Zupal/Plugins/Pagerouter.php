<?

class Zupal_Plugins_Pagerouter
extends Zend_Controller_Plugin_Abstract
{

	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $pRequest)
	{
		if (!$pRequest->getModuleName() || ($pRequest->getModuleName() == 'default')):
			$page = strtolower($pRequest->getParam('page', ''));

			if ($page):
				$pRequest->setModuleName('index');
				$pRequest->setControllerName('info');
				$pRequest->setActionName($page);
			endif;
		endif;
	}

	
}