<?php

class frontendConfiguration extends sfApplicationConfiguration
{
  public function configure()
  {
  	$this->dispatcher->connect('request.filter_parameters', array($this, 'filterRequestParameters'));
  }

  public function filterRequestParameters(sfEvent $event, $parameters)
  {
    $request = $event->getSubject();

    if (preg_match('#Mobile/.+Safari#i', $request->getHttpHeader('User-Agent')))
    {
      	$parameters["iphone"] = true;
    }

    return $parameters;
  }
}
