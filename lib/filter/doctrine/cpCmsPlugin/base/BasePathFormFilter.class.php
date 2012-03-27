<?php

/**
 * Path filter form base class.
 *
 * @package    Domain Checker
 * @subpackage filter
 * @author     Dave Walker
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePathFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'template_type'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'object_id'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'                 => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'                 => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'meta_page_title'            => new sfWidgetFormFilterInput(),
      'meta_navigation_title'      => new sfWidgetFormFilterInput(),
      'meta_path'                  => new sfWidgetFormFilterInput(),
      'meta_keywords'              => new sfWidgetFormFilterInput(),
      'meta_description'           => new sfWidgetFormFilterInput(),
      'meta_visible_in_navigation' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'root_id'                    => new sfWidgetFormFilterInput(),
      'lft'                        => new sfWidgetFormFilterInput(),
      'rgt'                        => new sfWidgetFormFilterInput(),
      'level'                      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'template_type'              => new sfValidatorPass(array('required' => false)),
      'object_id'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'                 => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'                 => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'meta_page_title'            => new sfValidatorPass(array('required' => false)),
      'meta_navigation_title'      => new sfValidatorPass(array('required' => false)),
      'meta_path'                  => new sfValidatorPass(array('required' => false)),
      'meta_keywords'              => new sfValidatorPass(array('required' => false)),
      'meta_description'           => new sfValidatorPass(array('required' => false)),
      'meta_visible_in_navigation' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'root_id'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lft'                        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'                        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'                      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('path_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Path';
  }

  public function getFields()
  {
    return array(
      'id'                         => 'Number',
      'template_type'              => 'Text',
      'object_id'                  => 'Number',
      'created_at'                 => 'Date',
      'updated_at'                 => 'Date',
      'meta_page_title'            => 'Text',
      'meta_navigation_title'      => 'Text',
      'meta_path'                  => 'Text',
      'meta_keywords'              => 'Text',
      'meta_description'           => 'Text',
      'meta_visible_in_navigation' => 'Boolean',
      'root_id'                    => 'Number',
      'lft'                        => 'Number',
      'rgt'                        => 'Number',
      'level'                      => 'Number',
    );
  }
}
