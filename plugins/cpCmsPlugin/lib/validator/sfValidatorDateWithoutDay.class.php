<?php
/**
 * This validator allows the use of month/year date input fields.
 * Note that you need to use this validator for *ALL* date fields for the form due to the order that Symfony processes.
 */
 
class sfValidatorDateWithoutDay extends sfValidatorDate
{

  protected function doClean($value)
  {
    if (!isset($value['day']))
    {
      $value['day'] = '1';
    }

    return parent::doClean($value);
  }
}
