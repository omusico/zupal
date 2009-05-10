<?php

class Zupal_Module_Logger
extends Zend_Log
{

/* @@@@@@@@@@@@@@@@@@ constructor @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function __construct($pName)
	{
		$pName = strtolower($pName);
		$this->set_name($pName);
		parent::__construct($this->get_stream());
		
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ get_stream @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return Zend_Log_Writer_Abstract
	*/
	public function get_stream ()
	{
		if (!Zupal_Bootstrap::$registry->configuration->logging->write):
			return new Zend_Log_Writer_Null();
		endif;

		if (Zupal_Bootstrap::$registry->configuration->logging->log_to_db):
			$table = Zupal_Eventlogs::getInstance()->table();
			$adapter = $table->getAdapter();
			return new Zend_Log_Writer_Db($adapter, $table->tableName());
		else:

			if (!is_dir($this->module_dir())):
				throw new Exception(__METHOD__ . ': Cannot find module ' . $this->get_name());
			endif;

			if (!is_dir($this->log_dir())):
				mkdir($this->log_dir(), 0775);
			endif;

			if (!file_exists($this->file())) touch($this->file());

			return new Zend_Log_Writer_Stream($this->file());
		endif;
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@ name @@@@@@@@@@@@@@@@@@@@@@@@ */

	private $_name = null;
	/**
	 * @return class;
	 */

	public function get_name() { return $this->_name; }

	public function set_name($pValue) { 
		$this->_name = $pValue;
		$this->setEventItem('module', strtolower($pValue));

	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ path @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return string
	*/
	public function module_dir()
	{
		return ZUPAL_MODULE_PATH . DS . $this->get_name();
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ log_dir @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return string
	*/
	public function log_dir()
	{
		return ZUPAL_MODULE_PATH . DS . $this->get_name() . DS . 'logs';
	}

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ file @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @return <type>
	*/
	public function file ()
	{
		return $this->log_dir() . DS . 'history.log';
	}
}