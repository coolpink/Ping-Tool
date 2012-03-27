<?php

/**
 * Check filter form base class.
 *
 * @package    Domain Checker
 * @subpackage filter
 * @author     Dave Walker
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCheckFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'status'     => new sfWidgetFormFilterInput(),
      'created'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'project_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'status'     => new sfValidatorPass(array('required' => false)),
      'created'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'project_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Project'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('check_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Check';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'status'     => 'Text',
      'created'    => 'Date',
      'project_id' => 'ForeignKey',
    );
  }
}
