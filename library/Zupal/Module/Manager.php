<?php

class Zupal_Module_Manager {
	
	private $_moduleDir;
	
	public function __construct() 
	{
		$this->_moduleDir = APPLICATION_PATH . DS . 'modules';
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ manager @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_modules = array();

	public function get_all()
	{
		return $this->_modules;
	}

	public function get($pManager)
	{
		load($pManager);
		return $this->_modules[$pManager];
	}

	public function load($pManager)
	{
		$pManager = strtolower(trim($pManager));

		if((!$pManager) || array_key_exists($pManager, $this->_modules))
		{
			return;
		}

		$item = new Zupal_Module_Manager_item($pManager);
		$this->_modules[$pManager] = $item;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ load_all @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return Zupal_Manager_item[]
	*/

	public function load_all ()
	{
		foreach($this->getModuleNames() as $module) $this->load($module);
	}
}
