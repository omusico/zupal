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
		@TODO;
		
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ instance @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_instance;

	/**
	 * A singleton accessor
	 * @return Zupal_Module_Manager
	 */
	public static function getInstance()
	{
		if (is_null(self::$_instance))
		{
			self::$_instance = new Zupal_Module_Manager();
		}

		return self::$_instance;
	}
}
