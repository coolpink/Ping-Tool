<?php

class sfValidatorGMapAddress extends sfValidatorBase
{
  protected function doClean($value)
  {
    try
    {
      $address = new sfValidatorString(array( 'min_length' => 10, 'max_length' => 255, 'required' => false ));
      $value['address'] = $address->clean(isset($value['address']) ? $value['address'] : null);
    }
    catch(sfValidatorError $e)
    {
      throw new sfValidatorError($this, 'invalid');
    }

    return $value;
  }
}