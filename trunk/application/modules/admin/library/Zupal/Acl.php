<?

class Zupal_Acl
extends Zend_Acl
{
/* @@@@@@@@@@@@@@@@@@ constructor @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */

	public function __construct()
	{
		foreach(Zupal_Roles::getInstance()->findAll() as $role):
			$this->addRole($role->as_acl_role());
		endforeach;



		foreach(Zupal_Grants::getInstance()->findAll() as $grant):
			if ($grant->allow):
				$this->allow($grant->role, $grant->resource, $privileges, $assert);
			endif;
		endforeach;
	}
	
}