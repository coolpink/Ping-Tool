<?php
// models/TimestampBehavior.php

class Doctrine_Template_Cmsable extends Doctrine_Template
{
  private $_parent = null;
  protected $_options = array();
  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);

    if (empty($this->_options["allowedChildren"]))
    {
      $this->_options["allowedChildren"] = array();
    }
  }
  public function setTableDefinition()
  {
    $this->actAs("Lockable");
    $this->hasColumn('path_id', 'integer', 4, array(
      'type' => 'integer',
      'length' => 4,
    ));

    $this->hasOne('Path', array(
      'local' => 'path_id',
      'foreign' => 'id',
      'onDelete' => 'CASCADE'
    ));

    $this->addListener(new CmsableListener);
  }

  public function getAllowedChildren()
  {

    return $this->_options["allowedChildren"];
  }

  public function isAncestorOf($child)
  {
    return substr($this->getFullUrl(), 0, strlen($child->getFullUrl())) == $child->getFullUrl();
  }

  /* forwarding methods to the node (with a twist) */
  public function getChildren($type = null, $include_invisible = true)
  {
    $r = array();
    $path = $this->getInvoker()->getPath();
    $children = $path->getNode()->getChildren();

    if ($children != null)
    {
      foreach ($children as $child)
      {
        if (!$include_invisible) {
          // check the child is visible in meta
          if (!$child->getMetaVisibleInNavigation()) {
            continue;
          }
        }

        if ($type != null) {
          if ($child->getTemplateType() == $type)
          {
            $r[] = $child->getObject();
          }
        } else {
          $r[] = $child->getObject();
        }
      }
    }
    return $r;
  }


  /* this wont work */
  public function getChildrenQuery($type = null, $include_invisible = true, $metapath = null)
  {
    $path = $this->getInvoker()->getPath();
    return $path->getChildrenQuery($type, $include_invisible, $metapath);

  }
  // woah woah woah there.
  public function getVisibleChildren($type = null)
  {
    return $this->getChildren($type, false);
  }

  public function getParent()
  {
    $parentNode = $this->getInvoker()->getPath()->getNode()->getParent();
    return $parentNode != null ? $parentNode->getObject() : null;
  }


  private function canUser($user, $action)
  {
    $object = $this->getInvoker();
    if (method_exists($object, "canUser".$action)){
      return $user->isSuperAdmin() || $object->{"canUser".$action}();
    }
    return true;
  }
  public function canUserDelete($user)
  {
    return $this->canUser($user, "Delete");
  }
  public function canUserMove($user)
  {
    return $this->canUser($user, "Move");
  }
  public function canUserEdit($user)
  {
    return $this->canUser($user, "Edit");
  }
  public function canUserCreate($user)
  {
    return $this->canUser($user, "Create");
  }

  /*
    forwarding methods over to the path
  */
  public function getMetaNavigationTitle()
  {

    return $this->getInvoker()->getPath()->getMetaNavigationTitle();
  }
  public function getMetaDescription()
  {
    return $this->getInvoker()->getPath()->getMetaDescription();
  }
  public function getMetaPageTitle()
  {
    return $this->getInvoker()->getPath()->getMetaPageTitle();
  }
  public function getMetaPath()
  {
    return $this->getInvoker()->getPath()->getMetaPath();
  }
  public function setNavTitle($val)
  {
    $this->getInvoker()->getPath()->setNavTitle($val);
  }

  public function getTemplateType()
  {

    return $this->getInvoker()->getPath()->getTemplateType();
  }
  public function setTemplateType($val)
  {
    $this->getInvoker()->getPath()->getTemplateType($val);
  }
  public function isRoot ()
  {
    return $this->getInvoker()->getPath()->getNode()->isRoot();
  }

  public function getVisibleInNavigation()
  {
    return $this->getInvoker()->getPath()->getMetaVisibleInNavigation();
  }
  public function setVisibleInNavigation($val)
  {
    return $this->getInvoker()->getPath()->setMetaVisibleInNavigation($val);
  }
  public function getFullUrl()
  {
    return $this->getInvoker()->getPath()->getFullUrl();
  }
  public function getLink()
  {
    return link_to($this->getMetaNavigationTitle(), $this->getFullUrl());
  }

}
