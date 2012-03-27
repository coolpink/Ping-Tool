<?php

/**
 *
 *
 * @package     cpMediaBrowser
 * @subpackage  form
 * @author      Coolpink <dev@coolpink.net>
 */
class cpMediaBrowserDirectoryForm extends sfForm
{
  protected $parent_dir;

  public function configure()
  {
    $this->setWidgets(array(
      'name'      => new sfWidgetFormInput(array(), array('placeholder' => 'new-folder')),
      'directory' => new sfWidgetFormInputHidden(array('default' => $this->parent_dir)),
    ));

    $this->widgetSchema->setNameFormat('directory[%s]');
    $this->getWidgetSchema()->setFormFormatterName('blank');

    $this->setValidators(array(
      'name'      => new sfValidatorString(array('trim' => true)),
      'directory' => new sfValidatorString(array('required' => false)),
    ));
    
    $this->getValidatorSchema()->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'postValidator')))
    );
  }
  
  public function postValidator($validator, $values)
  {
    $values['name'] = cpMediaBrowserStringUtils::slugify($values['name']);
    return $values;
  }

  public function setParentDirectory($parent_dir)
  {
    $this->parent_dir = $parent_dir;
  }
  
}