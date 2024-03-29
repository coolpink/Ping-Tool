<?php

/**
 * PluginsfGuardPermission form.
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: PluginsfGuardPermissionForm.class.php 24629 2009-12-01 00:34:36Z Jonathan.Wage $
 */
abstract class PluginsfGuardPermissionForm extends BasesfGuardPermissionForm
{
  /**
   * @see sfForm
   */
  public function setupInheritance()
  {
    parent::setupInheritance();

    unset($this['created_at'], $this['updated_at']);

    $this->widgetSchema['groups_list']->setLabel('Groups');
    $this->widgetSchema['users_list']->setLabel('Users');

    $this->widgetSchema->setHelps(array(
      "name"        => "The name of the permission",
      "description" => "A description of the permission",
      "groups_list" => "Which groups does this permission apply to?",
      "users_list"  => "Which individual users does this permission apply to?"
    ));
  }
}
