<?php

class Zupal_Module_Manager_Item
{

	public function __construct($pName)
	{
		$this->set_name($pName);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_name = null;
	/**
	 * @return class;
	 */

	public function get_name() { return $this->_name; }

	public function set_name($pValue) { $this->_name = $pValue; }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ info @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_info = NULL;
	function info($pReload = FALSE)
	{
		if ($pReload || is_null($this->_info)):
			$configFile = $this->directory() . DS . 'info.xml';

			if(file_exists($configFile)):
				// process
				$this->_info = new Zend_Config_Xml($configFile);
			else:							
				$configFile = preg_replace('~xml#~', 'ini', $configFile);

				if (file_exists($configFile)):
					$this->_info = new Zend_Config_Ini($configFile);
				else:
					throw new RuntimeException(sprintf("Module '%s' has no info.xml file.", $this->get_name()));
				endif;
			endif;		
			
		endif;
		return $this->_info;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ databases @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	
	private $_databases = NULL;
	function databases($pReload = FALSE)
	{
		if ($pReload || is_null($this->_databases)):
				$value = array();
			if ($this->info()->databases):
				foreach($this->info()->databases as $key => $db):
					if (!$db->adapter):
						$db->adapter = 'mysqli';
					endif;
					$value[$key] = Zend_DB::factory($db);
				endforeach;
			endif;		// process

			$this->_databases = $value;
		endif;

		return $this->_databases;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ directory @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return string
	*/
	public function directory ()
	{
		return ZUPAL_MODULE_PATH . DS . $this->get_name();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ add_paths @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return void
	*/
	public function add_paths ()
	{
		$paths = array();
		foreach(array('models', 'library') as $dir)
		{

			$path = $this->directory() . DS . $dir;
			if (is_dir($path))
			{
				$paths[] = $path;
			}
		}
		Zupal_Includes::add($paths);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ has @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pFile
	* @return int
	*/
	public function has ($pFile)
	{
		$full_path = $this->directory() . DS . ltrim($pFile, DS);

		if (is_dir($full_path)):
			if (file_exists($full_path)):
				return self::HAS_BOTH;
			else:
				return self::HAS_DIR;
			endif;
		elseif(file_exists($full_path)):
			return self::HAS_FILE;
		else:
			return self::HAS_NONE;
		endif;
	}

	const HAS_NONE = 0;
	const HAS_DIR  = 1;
	const HAS_FILE = 2;
	const HAS_BOTH = 3;

	public function has_admin(){ return $this->has(self::ADMIN_FILE) & self::HAS_FILE; }

	const ADMIN_FILE = 'controllers/AdminController.php';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ has_controller_action @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param $pController, $pItem
	* @return boolean
	*/
	public function has_controller_action ($pController, $pItem)
	{
		$pController = ucfirst($pController);
		$file = 'controllers/' . $pController . 'Controller.php';
		if (self::HAS_FILE & $this->has($file)):
			$this->include_file($file);
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ include @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pFile
	* @return <type>
	*/
	public function include_file ($pFile, $pOnce = TRUE)
	{
		if (self::HAS_FILE & $this->has($pFile)):
			if ($pOnce):
				include_once $this->directory() . DS . $pFile;
			else:
				include $this->directory() . DS . $pFile;
			endif;
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_file @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param string $pFile
	* @return string
	*/
	public function get_file ($pFile)
	{
		$path = $this->directory() . DS . $pFile;

		if (file_exists($path)):
			return file_get_contents($path);
		else:
			return NULL;
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ admin_menu_item @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	* @return <type>
	*/
	public function admin_menu_item ()
	{
		$mi = new Zupal_Menu_item($this->get_name(), $this->get_name(), 'admin');
		return $mi;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ logger @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_logger = array();
	/**
	 *
	 * @return Zupal_Module_Logger
	 */
	public function logger()
	{
		$module = strtolower($this->get_name());
		if ( !array_key_exists($module, self::$_logger)):
			// process
			self::$_logger[$module] = new Zupal_Module_Logger($module);
		endif;
		return self::$_logger[$module];
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ enabled @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return boolean
	*/
	public function enabled ()
	{
		return $this->module_record()->enabled;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ module_record @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_module_record = NULL;
	function module_record($pReload = FALSE)
	{
		if ($pReload || is_null($this->_module_record)):

			$value = Zupal_Modules::module($this->get_name());
			if (!$value->is_saved()):
				$value = new Zupal_Modules();
				$value->name = $this->get_name();
				$value->save();
			endif;
		// process
		$this->_module_record = $value;
		endif;
		return $this->_module_record;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ update_database @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return void;
	*/
	public function update_database ()
	{
		$mr = $this->module_record();
		$info = $this->info(TRUE);

		$mr->description = $info->description;
		$mr->required = $info->required;
		$mr->version = $info->version;
		$mr->package = strtolower($info->package);
		$mr->menu = $info->menu;
		$mr->save();
		return $mr;

	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ required @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return boolean
	*/
	public function required ()
	{
		return $this->info()->get('required', FALSE);
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __toString @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function __toString ()
	{
		return $this->get_name();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ search_logs @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pPhrase
	* @return array
	*/
	public function search_logs ($pPhrase, $pLines)
	{
		return $this->logger()->search_logs($pPhrase, $pLines);
	}
}

