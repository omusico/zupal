<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Session
 *
 * @author daveedelhart
 */
class Zupal_Session
{
	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ user_session @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	private static $_user_session = NULL;
	/**
	 * @return Zend_Session_Namespace
	 */
	public static function getInstance($pReload = FALSE)
	{
		$identity = Zend_Auth::getInstance()->hasIdentity() ? Zend_Auth::getInstance()->getIdentity() : FALSE;
		if (!$identity) {
			self::$_user_session = NULL;
			return NULL;
		}

		if ($pReload || is_null(self::$_user_session)):
			$session = new Zend_Session_Namespace('Zupal_User_Session');
			$session->user_id = $identity->identity();
			// process
			self::$_user_session = $session;
	//	elseif (self::$_user_session->user_id != $identity->identity()):
			//return get_user_session(TRUE);
		endif;
		return self::$_user_session;
	}

	

}