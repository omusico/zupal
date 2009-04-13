<?php
class Zupal_Module_Manager {
	
	public function getModuleNames() {
		$ignoreFiles = array(".svn","admin");
		
		$moduleNames = array();
		$moduleDir = dirname(Zend_Controller_Front::getInstance()->getModuleDirectory());		
		foreach (new DirectoryIterator($moduleDir) as $fileInfo) {	
			if($fileInfo->isDot()) continue;  // ignore . and ..
    		if(in_array($fileInfo->getFilename(),$ignoreFiles)) continue;  // ignore files
    		$moduleNames[] = $fileInfo->getFilename();
		}
		return $moduleNames;
	}
	
}
