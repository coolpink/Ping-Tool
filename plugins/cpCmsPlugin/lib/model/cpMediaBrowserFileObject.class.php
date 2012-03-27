<?php

/*
 * This file is part of the cpMediaBrowserPlugin package.
 * (c) Vincent Agnano <vincent.agnano@particul.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * cpMediaBrowserFileObject represents a file.
 *
 * @package    cpMediaBrowser
 * @subpackage model
 * @author     Coolpink <dev@coolpink.net>
 */
class cpMediaBrowserFileObject
{
  protected $file_url,
            $root_path,
            $name,
            $type,
            $size,
            $icon
            ;

  /**
   *
   * @param string $file the file path from under web_root
   */
  public function __construct($file, $root_path = null)
  {
    $this->file_url = $file;
    $this->root_path = $root_path ? realpath($root_path) : realpath(sfConfig::get('sf_web_dir'));
  }
  

  public function __toString()
  {
    return $this->getName();
  }
  
  
  public function exists()
  {
    return file_exists($this->getPath());
  }
  

  public function getType()
  {
    return cpMediaBrowserUtils::getTypeFromExtension($this->getExtension());
  }

  
  /**
   *
   * @return boolean
   */
  public function isImage()
  {
    return false;
  }

  /**
   *
   * @return string icon file name
   */
  public function getIcon()
  {
    if(!$this->icon)
    {
      $this->icon = cpMediaBrowserUtils::getIconFromExtension($this->getExtension());
    }
    return $this->icon;
  }
  

  public function getExtension()
  {
    return pathinfo($this->getUrl(), PATHINFO_EXTENSION);
  }


  public function getPath()
  {
    return realpath($this->cleanFolder($this->getRootPath().'/'.$this->getUrl()));
  }
  
  
  public function getUrl()
  {
    return $this->file_url;
  }
  
  
  public function getUrlDir()
  {
    return pathinfo($this->getUrl(), PATHINFO_DIRNAME);
  }


  public function getRootPath()
  {
    return realpath($this->cleanFolder($this->root_path));
  }

  
  public function getName($with_extension = true)
  {
    if(!$this->name)
    {
      $this->name = pathinfo($this->file_url, PATHINFO_FILENAME);
    }
    return $with_extension && $this->getExtension()
			      ? $this->name.'.'.$this->getExtension()
			      : $this->name
			      ;
  }

  public function getShortName($with_extension = true, $length = 16)
  {
    if(!$this->name)
    {
      $this->name = pathinfo($this->file_url, PATHINFO_FILENAME);
    }
    return $with_extension && $this->getExtension()
			      ? $this->truncateString($this->name.'.'.$this->getExtension(), $length)
			      : $this->truncateString($this->name, $length)
			      ;
  }

  protected function truncateString($string, $length)
  {
    if (strlen($string) <= $length) return $string;
    
    $separator = '...';
    $separatorlength = strlen($separator) ;
    $maxlength = $length - $separatorlength;
    $start = $maxlength / 2 ;
    $trunc =  strlen($string) - $maxlength;

    return substr_replace($string, $separator, $start, $trunc);
  }
  
  
  /**
   * Get a filesize
   * @return string The human readable value
   */
  public function getSize()
  {
    if(!$this->size)
    {
      $this->size = filesize($this->getPath());
    }
    $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
    if ($this->size == 0)
    {
      return('n/a');
    }
    else
    {
      return round($this->size/pow(1024, ($i = floor(log($this->size, 1024)))), 2) . $sizes[$i];
    }
  }


  protected function cleanFolder($folder)
  {
    $cleaned = preg_replace('`/+`', '/', $folder);
    $cleaned = substr($cleaned, -1, 1) == '/' ? substr($cleaned, 0, -1) : $cleaned;
    return $cleaned;
  }
  
  
  public function delete()
  {
    if($this->exists())
    {
      return unlink($this->getPath());
    }
    return false;
  }
}
