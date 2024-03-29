<?php

/**
 * BaseTeamMember
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $telephone
 * @property Doctrine_Collection $ProjectTeam
 * @property Doctrine_Collection $ProjectHasTeamMembers
 * 
 * @method integer             getId()                    Returns the current record's "id" value
 * @method string              getName()                  Returns the current record's "name" value
 * @method string              getEmail()                 Returns the current record's "email" value
 * @method string              getTelephone()             Returns the current record's "telephone" value
 * @method Doctrine_Collection getProjectTeam()           Returns the current record's "ProjectTeam" collection
 * @method Doctrine_Collection getProjectHasTeamMembers() Returns the current record's "ProjectHasTeamMembers" collection
 * @method TeamMember          setId()                    Sets the current record's "id" value
 * @method TeamMember          setName()                  Sets the current record's "name" value
 * @method TeamMember          setEmail()                 Sets the current record's "email" value
 * @method TeamMember          setTelephone()             Sets the current record's "telephone" value
 * @method TeamMember          setProjectTeam()           Sets the current record's "ProjectTeam" collection
 * @method TeamMember          setProjectHasTeamMembers() Sets the current record's "ProjectHasTeamMembers" collection
 * 
 * @package    Domain Checker
 * @subpackage model
 * @author     Dave Walker
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTeamMember extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('team_member');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('name', 'string', 45, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 45,
             ));
        $this->hasColumn('email', 'string', 45, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 45,
             ));
        $this->hasColumn('telephone', 'string', 45, array(
             'type' => 'string',
             'length' => 45,
             ));

        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Project as ProjectTeam', array(
             'refClass' => 'ProjectHasTeamMember',
             'local' => 'team_member_id',
             'foreign' => 'project_id'));

        $this->hasMany('ProjectHasTeamMember as ProjectHasTeamMembers', array(
             'local' => 'id',
             'foreign' => 'team_member_id'));
    }
}