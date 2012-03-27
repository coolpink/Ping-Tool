<?php

/**
 *
 *
 * @package     cpMediaBrowser
 * @subpackage  form
 * @author      Coolpink <dev@coolpink.net>
 */
class cpMediaBrowserUploadForm extends sfForm
{

  protected $upload_dir;

  public function configure()
  {
    $this->setWidgets(array(
      'file'      => new sfWidgetFormInputFile(),
      'directory' => new sfWidgetFormInputHidden(),
    ));

    $this->widgetSchema->setNameFormat('upload[%s]');
    $this->getWidgetSchema()->setFormFormatterName('blank');

    $this->setValidators(array(
      'file'      => new cpValidatorMediaBrowserFileUpload(array('path' => $this->getUploadDir())),
      'directory' => new sfValidatorString(array('required' => true)),
    ));
  }

  public function getUploadDir()
  {
    if(!$this->upload_dir)
    {
      $this->upload_dir = sfConfig::get('sf_upload_dir');
    }
    return $this->upload_dir;
  }

  public function setUploadDir($dir)
  {
    $this->upload_dir = $dir;
  }
}