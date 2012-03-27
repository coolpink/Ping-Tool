<?php

/**
 * ProjectHasTeamMember filter form base class.
 *
 * @package    Domain Checker
 * @subpackage filter
 * @author     Dave Walker
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseProjectHasTeamMemberFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
    ));

    $this->setValidators(array(
    ));

    $this->widgetSchema->setNameFormat('project_has_team_member_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProjectHasTeamMember';
  }

  public function getFields()
  {
    return array(
      'project_id'     => 'Number',
      'team_member_id' => 'Number',
    );
  }
}
