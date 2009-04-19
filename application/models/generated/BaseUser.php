<?php

/**
 * BaseUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $display_name
 * @property Doctrine_Collection $Roles
 * @property Doctrine_Collection $UserRole
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 5441 2009-01-30 22:58:43Z jwage $
 */
abstract class BaseUser extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('user');
        $this->hasColumn('id', 'integer', 11, array('type' => 'integer', 'autoincrement' => true, 'primary' => true, 'length' => '11'));
        $this->hasColumn('username', 'string', null, array('type' => 'string'));
        $this->hasColumn('password', 'string', null, array('type' => 'string'));
        $this->hasColumn('first_name', 'string', null, array('type' => 'string'));
        $this->hasColumn('last_name', 'string', null, array('type' => 'string'));
        $this->hasColumn('display_name', 'string', null, array('type' => 'string'));
    }

    public function setUp()
    {
        $this->hasMany('Role as Roles', array('refClass' => 'UserRole',
                                              'local' => 'user_id',
                                              'foreign' => 'role_id'));

        $this->hasMany('UserRole', array('local' => 'id',
                                         'foreign' => 'user_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}