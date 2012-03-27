<?php

/**
 * Path form base class.
 *
 * @method Path getObject() Returns the current form's model object
 *
 * @package    Domain Checker
 * @subpackage form
 * @author     Dave Walker
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePathForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                         => new sfWidgetFormInputHidden(),
      'template_type'              => new sfWidgetFormInputText(),
      'object_id'                  => new sfWidgetFormInputText(),
      'created_at'                 => new sfWidgetFormDateTime(),
      'updated_at'                 => new sfWidgetFormDateTime(),
      'meta_page_title'            => new sfWidgetFormInputText(),
      'meta_navigation_title'      => new sfWidgetFormInputText(),
      'meta_path'                  => new sfWidgetFormInputText(),
      'meta_keywords'              => new sfWidgetFormTextarea(),
      'meta_description'           => new sfWidgetFormTextarea(),
      'meta_visible_in_navigation' => new sfWidgetFormInputCheckbox(),
      'root_id'                    => new sfWidgetFormInputText(),
      'lft'                        => new sfWidgetFormInputText(),
      'rgt'                        => new sfWidgetFormInputText(),
      'level'                      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'template_type'              => new sfValidatorString(array('max_length' => 45)),
      'object_id'                  => new sfValidatorInteger(),
      'created_at'                 => new sfValidatorDateTime(),
      'updated_at'                 => new sfValidatorDateTime(),
      'meta_page_title'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'meta_navigation_title'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'meta_path'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'meta_keywords'              => new sfValidatorString(array('required' => false)),
      'meta_description'           => new sfValidatorString(array('required' => false)),
      'meta_visible_in_navigation' => new sfValidatorBoolean(array('required' => false)),
      'root_id'                    => new sfValidatorInteger(array('required' => false)),
      'lft'                        => new sfValidatorInteger(array('required' => false)),
      'rgt'                        => new sfValidatorInteger(array('required' => false)),
      'level'                      => new sfValidatorInteger(array('required' => false)),
    ));










    $this->widgetSchema->setNameFormat('path[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Path';
  }

}
