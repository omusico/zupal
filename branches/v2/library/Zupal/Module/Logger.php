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

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ search_logs @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
	/**
	*
	* @param <type> $pPhrase
	* @return array
	*/
	public function search_logs ($pPhrase, $pLines)
	{
		if(file_exists($this->file())):
			if ($pLines == 0):
				$log_lines = file($this->file());
			else:
				$size = filesize($this->file());
				$search_size = $pLines * 100;
				if($search_size >= $size): // the log is not that big
					$log_lines = file($this->file());
				else:
					$pass = 0;
					do
					{
						$log_lines = array();
						$h = fopen($this->file(), 'r');
						fseek($h, -1 * $search_size, SEEK_END);
						while(!feof($h)) $log_lines[] = fgets($h);
						fclose($h);
						array_shift($log_lines);
						error_log('******************** ' . __METHOD__ . ' pass ' . $pass++ . '***********');
						error_log(__METHOD__ . ': getting ' . $search_size . ' : text = ' . join("\n", $log_lines));
						$scaled_size = (int)($search_size * $pLines /count($log_lines));
						$search_size = max($search_size + 500, $scaled_size);
					}
					while(($search_size < $size) &&(count($log_lines) < $pLines));
					if (count($log_lines) < $pLines):
						$log_lines = file($this->file()); // if the last condition broke the loop, get all the lines
					endif;

				endif;

			endif;
			$out = array();

			Zupal_Bootstrap::$registry->log_set = $log_lines;
			$log_lines = array_slice($log_lines, -1 * $pLines);

			foreach($log_lines as $line):
				if (stristr($line, $pPhrase) !== FALSE):
					$out[] = $line;
				endif;
			endforeach;
			return $out;

		else: // no log file
			return array();
		endif;
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