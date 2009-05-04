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
		return APPLICATION_PATH . DS . 'modules' . DS . $this->get_name();
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
}

