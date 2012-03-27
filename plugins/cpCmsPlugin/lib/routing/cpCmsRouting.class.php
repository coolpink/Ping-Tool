<?php
/**
 * Created by IntelliJ IDEA.
 * User: Mikee
 * Date: 25-Jul-2010
 * Time: 18:59:24
 * To change this template use File | Settings | File Templates.
 */

class cpCmsRouting extends sfPatternRouting
{

  public function publicGetRouteThatMatchesUrl($url)
  {
    return $this->getRouteThatMatchesUrl($url);
  }

  protected function getRouteThatMatchesUrl($url)
  {
    $this->ensureDefaultParametersAreSet();
    foreach ($this->routes as $name => $route)
    {
      if ($name === "cms" || (substr_count($name, "cms_") > 0 && $parameters = $route->matchesUrl($url, $this->options['context'])))
      {
        return $this->lookForUrlInDatabase($url, $route, $parameters);
      }
      if (false === $parameters = $route->matchesUrl($url, $this->options['context']))
      {

        continue;
      }
      return array('name' => $name, 'pattern' => $route->getPattern(), 'parameters' => $parameters);
    }

    return false;
  }

  private function lookForUrlInDatabase($url, $route = null, $params = array())
  {
    $query = Doctrine_Core::getTable("Path")->createQuery("p")->where("level = ?", 0);
    if ($homePageTemplate = sfConfig::get('app_cms_routing_homepage_template'))
    {
      $query->addWhere("template_type = ?", $homePageTemplate);
    }
    $root = $query->fetchOne();
    $uri = substr($url, 1);
    $uri = trim($url, " /");
    $parts = explode("/", $uri);
    $currentNode = $root->getNode();
    $parameters = array();
    $isInPath = true;
    foreach ($parts as $part)
    {
      $broken = false;
      if ($isInPath)
      {
        $children = $currentNode->getChildren();
        if ($children != null)
        {
          foreach ($children as $child)
          {

            if ($child["meta_path"] == $part)
            {
              $currentNode = $child->getNode();
              $broken = true;
              break;
            }
          }
          if (!$broken)
          {
            $isInPath = false;
          }
        }
        else
        {
          $isInPath = false;
        }
      }
      if (!$isInPath)
      {
        $parameters[] = $part;
      }

    }

    $template_type = $currentNode->getRecord()->getTemplateType();
    if (method_exists(Doctrine_Core::getTable($template_type),'getParameterAction'))
    {
      if ($actions = Doctrine_Core::getTable($template_type)->getParameterAction($parameters))
      {
        if (!empty($actions))
        {
          array_unshift($parameters, $actions);
        }
      }
    }
    if (count($parameters) < 1 || trim($parameters[0]) == "")
    {
      $parameters[0] = "index";
    }

    $p = array(
      "module" => $template_type,
      "action" => array_shift($parameters)
    );

    $chunk = true;
    if (method_exists(Doctrine_Core::getTable($template_type),'getParameterNames'))
    {
      $newParams = array();
      $paramNames = Doctrine_Core::getTable($template_type)->getParameterNames();

      for ($i = 0; $i<count($paramNames); $i++)
      {
        if (!empty($parameters[$i]))
        {
          $newParams[$paramNames[$i]] = $parameters[$i];
        }
      }
      $parameters = $newParams;
      $chunk = false;
    }

    if ($route !== null)
    {
      if ($params)
      {
        $p = array_merge($p, $params);
      }
    }
    if ($chunk)
    {
      $chunked = array_chunk($parameters, 2);
      foreach ($chunked as $chunk)
      {
        $p[$chunk[0]] = isset($chunk[1]) ? $chunk[1] : true;
      }
    }
    else
    {
      $p = array_merge($p, $parameters);
    }
    $this->routes["cms"]->setObject($currentNode->getRecord()->getObject());

    return array('name' => "cms", 'pattern' => $this->routes["cms"]->getPattern(), 'parameters' => $p);
  }

  public function getRouteByKey($key)
  {
    return $this->routes[$key];
  }

  static public function addRouteForAdminCms(sfEvent $event)
  {

    $event->getSubject()->prependRoute('cms', new sfDoctrineRouteCollection(array(
                                                                                 'name' => 'path',
                                                                                 'model' => 'Path',
                                                                                 'module' => 'cpCms',
                                                                                 'prefix_path' => 'path',
                                                                                 'with_wildcard_routes' => true,
                                                                                 'collection_actions' => array('filter' => 'post', 'batch' => 'post'),
                                                                                 'requirements' => array(),
                                                                            )));

  }

}
