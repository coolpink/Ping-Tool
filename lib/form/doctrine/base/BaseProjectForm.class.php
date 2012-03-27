<?php

/**
 * Project form base class.
 *
 * @method Project getObject() Returns the current form's model object
 *
 * @package    Domain Checker
 * @subpackage form
 * @author     Dave Walker
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProjectForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'domain'            => new sfWidgetFormInputText(),
      'client_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Client'), 'add_empty' => false)),
      'project_team_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'TeamMember')),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'domain'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'client_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Client'))),
      'project_team_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'TeamMember', 'required' => false)),
    ));










    $this->widgetSchema->setNameFormat('project[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Project';
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
