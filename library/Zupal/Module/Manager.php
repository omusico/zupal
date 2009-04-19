<?php

class Zupal_Module_Manager {
	
	private $_moduleDir;
	
	public function __construct() 
	{
		$this->_moduleDir = dirname(
			Zend_Controller_Front::getInstance()->getModuleDirectory()
			);	
	}
	

	public function getModuleInfo($moduleName) 
	{
		$configFile = $this->_moduleDir . DS . $moduleName . DS . 'info.xml';
		
		if(!file_exists($configFile)) 
		{
			throw new RuntimeException(sprintf("Module '%s' has no info.xml file.", $moduleName));
		}
		
		return new Zend_Config_Xml($configFile);				 		
	}
	
	public function getInstalledModules() {
		$installedModules = Doctrine::getTable('Module')->findAll(Doctrine::HYDRATE_ARRAY);
		
		
	}
	
	public function getModuleNames() 
	{
		$ignoreFiles = array('.svn','default','admin');
		
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
