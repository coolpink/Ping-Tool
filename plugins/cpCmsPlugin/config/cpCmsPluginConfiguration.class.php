<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * cpCmsPluginConfiguration configuration.
 *
 * @package    cpCmsPluginConfiguration
 * @subpackage config
 * @author     Mike Franklin <mikee@coolpink.net>
 * @version    SVN: $Id: cpCmsPluginConfiguration.class.php
 */
class cpCmsPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    if (in_array("cpCms", sfConfig::get('sf_enabled_modules', array())))
    {
      $this->dispatcher->connect('routing.load_configuration', array('cpCmsRouting', 'addRouteForAdminCms'));
    }
    if (sfConfig::get('app_sf_guard_plugin_routes_register', true) && in_array('sfGuardAuth', sfConfig::get('sf_enabled_modules', array())))
    {
      $this->dispatcher->connect('routing.load_configuration', array('sfGuardRouting', 'listenToRoutingLoadConfigurationEvent'));
    }

    foreach (array('sfGuardUser', 'sfGuardGroup', 'sfGuardPermission', 'sfGuardRegister', 'sfGuardForgotPassword') as $module)
    {
      if (in_array($module, sfConfig::get('sf_enabled_modules', array())))
      {
        $this->dispatcher->connect('routing.load_configuration', array('sfGuardRouting', 'addRouteFor'.str_replace('sfGuard', '', $module)));
      }
    }
  }
}
