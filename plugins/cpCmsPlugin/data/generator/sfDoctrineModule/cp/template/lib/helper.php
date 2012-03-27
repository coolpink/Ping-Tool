[?php

/**
 * <?php echo $this->getModuleName() ?> module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: helper.php 12482 2008-10-31 11:13:22Z fabien $
 */
class Base<?php echo ucfirst($this->getModuleName()) ?>GeneratorHelper extends sfModelGeneratorHelper
{
  public function linkToShow($object, $params)
  {
    return '<li class="sf_admin_action_show">'.link_to("<span>".__($params['label'], array(), 'sf_admin')."</span>"."</span>", $this->getUrlForAction('show'), $object, $params['params']).'</li>';
  }

  public function linkToNew($params)
  {
    return '<li class="sf_admin_action_new">'.link_to("<span>".__($params['label'] , array(), 'sf_admin')."</span>", '@'.$this->getUrlForAction('new'),$params['params']).'</li>';
  }

  public function linkToEdit($object, $params)
  {
    return '<li class="sf_admin_action_edit">'.link_to("<span>".__($params['label'], array(), 'sf_admin')."</span>", $this->getUrlForAction('edit'), $object, $params['params']).'</li>';
  }

  public function linkToDelete($object, $params)
  {
    if ($object->isNew())
    {
      return '';
    }
    return '<li class="sf_admin_action_delete">'.link_to("<span>". __($params['label'], array(), 'sf_admin')."</span>", $this->getUrlForAction('delete'), $object, array('method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], array(), 'sf_admin') : $params['confirm'])).'</li>';
  }
  public function getLinkToAction($moduleName, $actionName, $params, $pk_link = false)
  {
    $action = isset($params['action']) ? $params['action'] : 'List'.sfInflector::camelize($actionName);

    return '<li>'.link_to("<span>".$params['label']."</span>", $moduleName."/".$action).'</li>';
  }

  public function linkToList($params)
  {
    return '<li class="sf_admin_action_list">'.link_to("<span>".__($params['label'], array(), 'sf_admin')."</span>", '@'.$this->getUrlForAction('list'),$params['params']).'</li>';
  }


  public function getUrlForAction($action)
  {
    return 'list' == $action ? '<?php echo $this->params['route_prefix'] ?>' : '<?php echo $this->params['route_prefix'] ?>_'.$action;
  }
}