<?php

/**
 * CmsableListener
 *
 * This class provides Doctrine record hooks for all model objects with the Cmsable behaviour
 *
 * @package    Caching
 * @author     Jon Cotton <jon@coolpink.net>
 */
class CmsableListener extends Doctrine_Record_Listener
{


  /**
   * postSave - Doctrine record hook that is called after each associated model object is saved
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function postSave(Doctrine_Event $event)
  {
    $sf_cache_dir = sfConfig::get('sf_cache_dir');
    $cacheDir = $sf_cache_dir.DIRECTORY_SEPARATOR.'frontend/*/template/*/all';
    sfToolkit::clearGlob($cacheDir);
    if ($memcache_driver = Doctrine_Manager::getInstance()->getAttribute(Doctrine::ATTR_RESULT_CACHE))
    {
    	$memcache_driver->deleteAll();
    }
  }
}