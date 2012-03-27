<?php

/**
 *
 *
 * @package     cpMediaBrowser
 * @author      Coolpink <dev@coolpink.net>
 */
class BasecpMediaBrowserComponents extends sfComponents
{
  public function executeIcon(sfWebRequest $request)
  {
    $class = cpMediaBrowserUtils::getTypeFromExtension(cpMediaBrowserUtils::getExtensionFromFile($this->file_url)) == 'image'
           ? 'cpMediaBrowserImageObject'
           : 'cpMediaBrowserFileObject'
           ;
    $this->file = new $class($this->file_url);
  }

  public function executeTree(sfWebRequest $request)
  {
    $this->structure = $this->makeUl($this->structure, $this->root_dir, true);
  }

  public function makeUl($array, $root_dir, $initial = false) {
    $result = $initial ?
        '<ul><li class="jstree-open">' . link_to('/', 'cp_media_browser', array_merge($this->current_params, array('dir' => $root_dir))) . '<ul>':
        '<ul>';
    foreach ($array as $nodeName => $children) {
      $result .= (strpos(htmlentities($this->relative_dir), htmlentities($root_dir . '/' . $nodeName)) !== false && count($children)) ?
          '<li class="jstree-open">' :
          '<li>' ;
      $result .= link_to($nodeName, 'cp_media_browser', array_merge($this->current_params, array('dir' => $root_dir . '/' . htmlentities($nodeName))));
      if (count($children)) {
          $result .= $this->makeUl($children, $root_dir . '/' . $nodeName);
      }
      $result .= '</li>';
    }
    $result .= $initial ?
        '</ul></li></ul>' :
        '</ul>';
    return $result;
  }

}
