<?php

class Zupal_People_Authorizer
implements Zend_Auth_Adapter_Interface 
{
	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ __construct @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
		
		public function __construct($username, $password)
		{
			$this->set_username($username);
			$this->set_password($password);
		}
		
		
	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ username @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
		
		private $_username = NULL;
		/**
		  * @return scalar
		  */
		public function get_username(){ return $this->_username; }
		public function set_username($value){ $this->_username = $value; }	
		
		
	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ password @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
		
		private $_password = NULL;
		/**
		  * @return scalar
		  */
		public function get_password(){ return $this->_password; }
		public function set_password($value){ $this->_password = $value; }	
		
   /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot
     *                                     be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
    	$stub = new Zupal_People(Zupal_Domain::STUB);
		$user = $stub->find_one(
    		array( 'username' => array($this->get_username(), 'LIKE'))
    	);
    	
		if ($user && $user->is_saved() &&
			Zupal_Poeple::encrypt_password($this->get_password(), $user->identity()) == $user->password):
    		$result = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $user);
    	else:
    		$result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE, NULL);
    	endif;
    	return $result;
    }
} 
