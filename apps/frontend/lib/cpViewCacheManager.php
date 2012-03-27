<?php

/**
 * cpViewCacheManager class file.
 *
 * PHP version 5
 *
 * @category Symfony
 * @package  Coolpink
 * @author   Mikee Franklin <mikee@coolpink.net>
 * @link     https://github.com/coolpink/WrenKitchens
 */

/**
 * cpViewCacheManager class.
 *
 * @category   Symfony
 * @package    Domain Checker
 * @subpackage Frontend
 * @author     Dave Walker <dave.walker@coolpink.net>
 * @license    http://en.wikipedia.org/wiki/Software_copyright Copyright Coolpink
 * @version    1
 * @link       https://github.com/coolpink/WrenKitchens
 */
class cpViewCacheManager extends sfViewCacheManager
{
  public function isCacheable($internalUri)
  {
    list($route_name, $params) = $this->controller->convertUrlStringToParameters($internalUri);

    if ($route_name != 'sf_cache_partial' && $this->request instanceof sfWebRequest && !$this->request->isMethod(sfRequest::GET))
    {
      return false;
    }

    if (!isset($params['module']))
    {
      return false;
    }

    $this->registerConfiguration($params['module']);

    if (isset($this->cacheConfig[$params['module']][$params['action']]))
    {
      return ($this->cacheConfig[$params['module']][$params['action']]['lifeTime'] > 0);
    }
    else
    {
      if (isset($this->cacheConfig[$params['module']]['DEFAULT']))
      {
        return ($this->cacheConfig[$params['module']]['DEFAULT']['lifeTime'] > 0);
      }
    }

    return false;
  }

  public function isActionCacheable($moduleName, $actionName)
  {
    $this->registerConfiguration($moduleName);

    if (isset($this->cacheConfig[$moduleName][$actionName]))
    {
      return $this->cacheConfig[$moduleName][$actionName]['lifeTime'] > 0;
    }
    else
    {
      if (isset($this->cacheConfig[$moduleName]['DEFAULT']))
      {
        return $this->cacheConfig[$moduleName]['DEFAULT']['lifeTime'] > 0;
      }
    }

    return false;
  }
}
