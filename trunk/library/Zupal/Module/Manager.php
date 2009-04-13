<?php

class Zupal_Module_Manager {
	
	private $_moduleDir;
	
	public function __construct() 
	{
			$this->_moduleDir = 
				dirname(Zend_Controller_Front::getInstance()->getModuleDirectory());	
	}
	
	public function getModuleConfig($moduleName) 
	{
		$configFile = $this->_moduleDir . DS . $moduleName . DS . 'configuration.xml';
		return new Zend_Config_Xml($configFile);				 		
	}
	
	public function getModuleNames() 
	{
		$ignoreFiles = array(".svn","admin");
		
		$moduleNames = array();
	
		foreach (new DirectoryIterator($this->_moduleDir) as $fileInfo) 
		{	
			
			if($fileInfo->isDot()) continue;  // ignore . and ..
    		
			if(in_array($fileInfo->getFilename(),$ignoreFiles)) continue;  // ignore files
    		
    		if($fileInfo->isDir()) 
    		{
    			$moduleNames[] = $fileInfo->getFilename();
			}
		}
		return $moduleNames;
	}
	
}
