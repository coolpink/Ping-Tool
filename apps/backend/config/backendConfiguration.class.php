<?php

/**
 * backendConfiguration class file.
 *
 * PHP version 5
 *
 * @category Symfony
 * @package  Coolpink
 * @author   Mikee Franklin <mikee@coolpink.net>
 * @link     https://github.com/coolpink/WrenKitchens
 */

/**
 * backendConfiguration class.
 *
 * @category   Symfony
 * @package    Domain Checker
 * @subpackage Backend
 * @author     Dave Walker <dave.walker@coolpink.net>
 * @license    http://en.wikipedia.org/wiki/Software_copyright Copyright Coolpink
 * @version    1
 * @link       https://github.com/coolpink/WrenKitchens
 */
class backendConfiguration extends sfApplicationConfiguration
{
  public function configure()
  {
  }

  public function getDecoratorDirs()
  {
    $dirs = parent::getDecoratorDirs();
    if ($config = ProjectConfiguration::getActive())
    {
      foreach ($config->getPlugins() as $plugin)
      {
        array_unshift($dirs, sfConfig::get('sf_plugins_dir') . '/' . $plugin . '/templates');
      }
    }
    return $dirs;
  }
}
