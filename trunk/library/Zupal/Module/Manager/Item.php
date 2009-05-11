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

