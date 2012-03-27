<?php
/*
 * This file is part of the cpMediaBrowser package.
 *
 * Original sfMediaBrowserPlugin (c) 2009 Vincent Agnano <vincent.agnano@particul.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package cpMediaBrowser
 * @author Coolpink <dev@coolpink.net>
 *
 */
class cpMediaBrowserUtils
{
  protected $type_extension = array(
    'document' => array('doc', 'xls', ''),
  );

  public static $icons_path;

  static public function getTypeFromExtension($extension)
  {
    $extension = self::cleanString($extension);
    $types = self::getFileTypes();
    foreach($types as $type => $data)
    {
      if(in_array($extension, $data['extensions']))
      {
        return $type;
      }
    }
    return 'file';
  }


  static public function getIconFromType($type)
  {
    $types = self::getFileTypes();
    $dir = self::getIconsDir();
    if(array_key_exists($type, $types))
    {
      $icon = array_key_exists('icon', $types[$type])
            ? $types[$type]['icon']
            : $type
            ;
      return $dir.'/'.$icon.'_'.sfConfig::get('app_cp_media_browser_thumbnails_max_width', '90').'.png';
    }
    return $dir.'/file_'.sfConfig::get('app_cp_media_browser_thumbnails_max_width', '90').'.png';

  }


  static public function getNameFromFile($file)
  {
    $dot_position = strrpos($file, '.');
    return $dot_position ? substr($file, 0, $dot_position) : $file;
  }


  static public function getExtensionFromFile($file)
  {
    return strtolower(substr(strrchr($file, '.'), 1));
  }


  static public function getIconFromExtension($extension)
  {
    $dir = '/cpCmsPlugin/images/icons';
    $path = self::getIconsPath();
    if(file_exists($path.'/'.$extension.'.png'))
    {
      return $dir.'/'.$extension.'_'.sfConfig::get('app_cp_media_browser_thumbnails_max_width', '90').'.png';
    }
    return self::getIconFromType(self::getTypeFromExtension($extension));
  }

  static public function getTypeFromMime($file)
  {

  }


  static public function getFileTypes()
  {
    return sfConfig::get('app_cp_media_browser_file_types', array());
  }


  /**
   * Clean a string : lower cased and trimmed
   * @param string string to clean
   * @return string cleaned string
   */
  static public function cleanString($value)
  {
    return strtolower(trim($value));
  }


  static public function getIconsPath()
  {
    if(!self::$icons_path)
    {
      self::$icons_path = sfConfig::get('sf_web_dir').self::getIconsDir();
    }
    return self::$icons_path;
  }

  static public function getIconsDir()
  {
    return '/cpCmsPlugin/images/icons';
  }


  static public function deleteRecursive($path)
  {
    $files = sfFinder::type('file')->ignore_version_control(false)->in($path);
    foreach($files as $file)
    {
      unlink($file);
    }
    $dirs = array_reverse(sfFinder::type('dir')->ignore_version_control(false)->in($path));
    foreach($dirs as $dir)
    {
      rmdir($dir);
    }
    return rmdir($path);
  }


  static public function loadAssets(array $assets, sfResponse $response = null, $position = '')
  {
    $response = $response ? $response : sfContext::getInstance()->getResponse();

    if(isset($assets['js']) && !empty($assets['js']))
    {
      foreach((array) $assets['js'] as $js)
      {
        $response->addJavascript($js, $position);
      }
    }
    if(isset($assets['css']) && !empty($assets['css']))
    {
      foreach((array) $assets['css'] as $css)
      {
        $response->addStylesheet($css, $position);
      }
    }
  }
}