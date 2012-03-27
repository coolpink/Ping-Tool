<?php

/**
 * ProjectHasTeamMember form base class.
 *
 * @method ProjectHasTeamMember getObject() Returns the current form's model object
 *
 * @package    Domain Checker
 * @subpackage form
 * @author     Dave Walker
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProjectHasTeamMemberForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'project_id'     => new sfWidgetFormInputHidden(),
      'team_member_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'project_id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('project_id')), 'empty_value' => $this->getObject()->get('project_id'), 'required' => false)),
      'team_member_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('team_member_id')), 'empty_value' => $this->getObject()->get('team_member_id'), 'required' => false)),
    ));










    $this->widgetSchema->setNameFormat('project_has_team_member[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProjectHasTeamMember';
  }

}
