<?php

/**
 *
 *
 * @package     cpMediaBrowser
 * @subpackage  form
 * @author      Coolpink <dev@coolpink.net>
 */
class cpMediaBrowserFileRenameForm extends sfForm
{

  public function configure()
  {
    $this->setWidgets(array(
      'new_name'     => new sfWidgetFormInput(),
      'current_name' => new sfWidgetFormInputHidden(),
      'directory'    => new sfWidgetFormInputHidden(),
    ));

    $this->widgetSchema->setNameFormat('rename[%s]');
    $this->getWidgetSchema()->setFormFormatterName('blank');

    $this->setValidators(array(
      'new_name'      => new sfValidatorString(array('trim' => true)),
      'current_name'  => new sfValidatorString(),
      'directory'     => new sfValidatorString(array('required' => false)),
    ));
  }
  
}