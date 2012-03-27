<?php

/*
 * This file is part of the cpMediaBrowser package.
 *
 * Original sfMediaBrowserPlugin (c) 2009 Vincent Agnano <vincent.agnano@particul.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * cpValidatorMediaBrowser validates a selected file.
 *
 * @package    cpMediaBrowserPlugin
 * @subpackage validator
 * @author     Coolpink <dev@coolpink.net>
 */
class cpValidatorMediaBrowserFileUpload extends sfValidatorBase
{
  /**
   * Configures the current validator.
   * 
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->setMessage('invalid', '"%file%" is not a valid file path.');
    $this->addOption('type');
    $this->addMessage('type', 'the file must be a "%type%"');
    $this->addOption('path', null);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    if (isset($value['name']))
    {
      // Must be a direct form submit
      $value = array($value);
    }

    foreach ($value as $key => $file)
    {
      try
      {
        $fileValidator = new sfValidatorFile(array('path' => $this->getOption('path')));
        $value[$key] = $fileValidator->clean($file);
      }
      catch(sfValidatorError $e)
      {
        throw new sfValidatorError($this, 'invalid');
      }
    }

    return $value;
  }
}
