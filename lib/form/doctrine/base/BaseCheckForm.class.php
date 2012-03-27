<?php

/**
 * Check form base class.
 *
 * @method Check getObject() Returns the current form's model object
 *
 * @package    Domain Checker
 * @subpackage form
 * @author     Dave Walker
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCheckForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'status'     => new sfWidgetFormInputText(),
      'created'    => new sfWidgetFormDateTime(),
      'project_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'status'     => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'created'    => new sfValidatorDateTime(array('required' => false)),
      'project_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Project'))),
    ));










    $this->widgetSchema->setNameFormat('check[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Check';
  }

}
