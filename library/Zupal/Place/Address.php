<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Address
 *
 * @author daveedelhart
 */
class Zupal_Place_Address
implements Zupal_Place_IItem
{
	public function __construct($pAddress, $pAddress2)
	{
		$this->_address_parts = is_array($pAddress) ? $pAddress : func_get_args();
	}

	private $_address_parts = array('');

	public function get_value();
	public function set_value($pString){
		if (func_num_args() == 1)
		{
			if (is_array($pString)):
				$this->_address_parts = $pString;
			elseif (is_string($pString)):
				$this->_address_parts = split("\n", $pString);
			elseif (is_object($pString)):
				$this->_address_parts[0] = $pString->address;
				$this->_address_parts[1] = $pString->address_2;
			endif;
		}
	}
	public function __toString()
	{
		return trim(join("\n", $this->_address_parts));
	}

	public function __get($pField)
	{
		switch(strtolower($pField)):
			case 'address':
				return $this->_address_parts[0];
			break;

			case 'address_2':
				if (array_key_exists(1, $this->_address_parts)):
					return $this->_address_parts[1];
				else:
					return NULL;
				endif;
			break;

			default:
				throw new Exception(__METHOD__ . ': requested field ' . $pField);
		endswitch;
	}

	public function __set($pField, $pValue)
	{
		switch(strtolower($pField)):
			case 'address':
				$this->_address_parts[0] = $pValue;
			break;

			case 'address_2':
				if (is_null($pValue) && array_key_exists(1, $this->_address_parts)):
					unset($this->_address_parts[1]);
				else:
					$this->_address_parts[1] = $pValue;
				endif;
			break;

			default:
				throw new Exception(__METHOD__ . ': requested field ' . $pField);
		endswitch;
	}
}
