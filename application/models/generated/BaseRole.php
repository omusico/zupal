<?php

/**
 * BaseRole
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property Doctrine_Collection $Users
 * @property Doctrine_Collection $UserRole
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 5441 2009-01-30 22:58:43Z jwage $
 */
abstract class BaseRole extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('role');
        $this->hasColumn('id', 'integer', 11, array('type' => 'integer', 'autoincrement' => true, 'primary' => true, 'length' => '11'));
        $this->hasColumn('name', 'string', 255, array('type' => 'string', 'length' => '255'));
    }

    public function setUp()
    {
        $this->hasMany('User as Users', array('refClass' => 'UserRole',
                                              'local' => 'role_id',
                                              'foreign' => 'user_id'));

        $this->hasMany('UserRole', array('local' => 'id',
                                         'foreign' => 'role_id'));
    }
}