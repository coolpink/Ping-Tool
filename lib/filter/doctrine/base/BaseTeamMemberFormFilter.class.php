<?php

/**
 * TeamMember filter form base class.
 *
 * @package    Domain Checker
 * @subpackage filter
 * @author     Dave Walker
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTeamMemberFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'email'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'telephone'         => new sfWidgetFormFilterInput(),
      'project_team_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Project')),
    ));

    $this->setValidators(array(
      'name'              => new sfValidatorPass(array('required' => false)),
      'email'             => new sfValidatorPass(array('required' => false)),
      'telephone'         => new sfValidatorPass(array('required' => false)),
      'project_team_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Project', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('team_member_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addProjectTeamListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.ProjectHasTeamMember ProjectHasTeamMember')
      ->andWhereIn('ProjectHasTeamMember.project_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'TeamMember';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'name'              => 'Text',
      'email'             => 'Text',
      'telephone'         => 'Text',
      'project_team_list' => 'ManyKey',
    );
  }
}
