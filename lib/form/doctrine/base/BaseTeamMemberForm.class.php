<?php

/**
 * TeamMember form base class.
 *
 * @method TeamMember getObject() Returns the current form's model object
 *
 * @package    Domain Checker
 * @subpackage form
 * @author     Dave Walker
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTeamMemberForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'name'              => new sfWidgetFormInputText(),
      'email'             => new sfWidgetFormInputText(),
      'telephone'         => new sfWidgetFormInputText(),
      'project_team_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Project')),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'              => new sfValidatorString(array('max_length' => 45)),
      'email'             => new sfValidatorString(array('max_length' => 45)),
      'telephone'         => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'project_team_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Project', 'required' => false)),
    ));










    $this->widgetSchema->setNameFormat('team_member[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TeamMember';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['project_team_list']))
    {
      $this->setDefault('project_team_list', $this->object->ProjectTeam->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveProjectTeamList($con);

    parent::doSave($con);
  }

  public function saveProjectTeamList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['project_team_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->ProjectTeam->getPrimaryKeys();
    $values = $this->getValue('project_team_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('ProjectTeam', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('ProjectTeam', array_values($link));
    }
  }

}
