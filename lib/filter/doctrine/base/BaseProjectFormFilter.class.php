<?php

/**
 * Project filter form base class.
 *
 * @package    Domain Checker
 * @subpackage filter
 * @author     Dave Walker
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseProjectFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'domain'            => new sfWidgetFormFilterInput(),
      'client_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Client'), 'add_empty' => true)),
      'project_team_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'TeamMember')),
    ));

    $this->setValidators(array(
      'domain'            => new sfValidatorPass(array('required' => false)),
      'client_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Client'), 'column' => 'id')),
      'project_team_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'TeamMember', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('project_filters[%s]');

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
      ->andWhereIn('ProjectHasTeamMember.team_member_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Project';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'domain'            => 'Text',
      'client_id'         => 'ForeignKey',
      'project_team_list' => 'ManyKey',
    );
  }
}
