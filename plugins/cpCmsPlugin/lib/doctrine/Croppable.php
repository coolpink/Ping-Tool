<?php

class Doctrine_Template_Listener_Croppable extends Doctrine_Record_Listener
{
  /**
  * Array of ageable options
  *
  * @var array
  */
  protected $_options = array();

  /**
  * __construct
  *
  * @param array $options
  * @return void
  */
  public function __construct(array $options)
  {
    $this->_options = $options;
  }

  public function preInsert(Doctrine_Event $event)
  {
    $this->checkImages($event);
  }

  public function preUpdate(Doctrine_Event $event)
  {

    $this->checkImages($event);
  }

  /**
   * Removes all image files when the record is deleted
   * Thanks to Shane McKinley for providing this fix
   *
   * @param Doctrine_Event $event
   */
  public function preDelete(Doctrine_Event $event)
  {
    $invoker = $event->getInvoker();

    if (!method_exists($invoker, 'removeImages'))
    {
      return;
    }

    foreach ($this->_options['images'] as $fieldName)
    {
      $invoker->removeImages($fieldName, $invoker->$fieldName);
    }
  }

  private function checkImages(Doctrine_Event $event) {

    $invoker = $event->getInvoker();

    $oldValues = $invoker->getModified(true);

    $modifiedFields = array_keys($invoker->getModified());



    $imageFieldSuffixes = array('', '_x1', '_y1', '_x2', '_y2');

    foreach ($this->_options['images'] as $imageName) {

      $needsUpdate = false;

      foreach ($imageFieldSuffixes as $suff) {

        if (in_array($imageName . $suff, $modifiedFields)) {
          $needsUpdate = true;
          break;
        }

      }
      if ($needsUpdate) {
        $invoker->updateImage($imageName);
      }
    }
  }
}