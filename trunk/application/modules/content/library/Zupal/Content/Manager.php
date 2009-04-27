<?php

class Zupal_Content_Module
implements Zupal_Module_IModule
{

	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ info @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_info = NULL;
	function info($pReload = FALSE)
	{
		if ($pReload || is_null($this->_info)):
			$value = new Zend_Config_Xml($this->root() . DS . 'info.xml');
			// process
			$this->_info = $value;
		endif;
		return $this->_info;
	}

	function install()
	{
		Zupal_Table_Content::install();
	}

	function unistall()
	{

	}

	function can_install() {
		return TRUE;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ root @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_root = NULL;
	function root($pReload = FALSE)
	{
		if ($pReload || is_null($this->_root)):
			$value = realpath(dirname(__FILE__) . '/../../../');
		// process
			$this->_root = $value;
		endif;
		return $this->_root;
	}
}