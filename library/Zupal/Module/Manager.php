<?php

class Zupal_Module_Manager {
	
	private $_moduleDir;
	
	public function __construct() 
	{
		$this->_moduleDir = ZUPAL_APPLICATION_PATH . DS . 'modules';
	}
	
	public function getInstalledModules() {
		@TODO;
		
	}
	
	public function getModuleNames() 
	{
		$ignoreFiles = array('.svn');
		
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
/**
 *
 * @param string $pManager
 * @return Zupal_Manager_Item
 */
	public function get($pManager)
	{
		if ($pManager):
			$this->load($pManager);
			return $this->_modules[$pManager];
		endif;
	}

	public function load($pManager)
	{
		$pManager = strtolower(trim($pManager));

		if((!$pManager) || array_key_exists($pManager, $this->_modules))
		{
			return;
		}

		$item = new Zupal_Module_Manager_Item($pManager);
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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ update_database @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return void
	*/
	public function update_database ()
	{
		$cache = Zupal_Bootstrap::$registry->cache;

		foreach($this->get_all() as $item):
			$item->update_database();
		endforeach;
		$cache->remove('modules_data');
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ databases @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_databases = NULL;
	function databases($pReload = FALSE)
	{
		if ($pReload || is_null($this->_databases)):
			$list = array();
			foreach($this->get_all() as $module):
				$list = array_merge($list, $module->databases());
			endforeach;
		// process
			$this->_databases = $list;
		endif;
		return $this->_databases;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ database @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pName
	* @return <type>
	*/
	public function database ($pName)
	{
		$db_list = $this->databases();

		return $db_list[$pName];
	}
}
