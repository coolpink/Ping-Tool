<?php

/*
 * This file is part of the cpMediaBrowserPlugin package.
 * (c) Vincent Agnano <vincent.agnano@particul.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormInput represents an HTML input file browser tag.
 *
 * @package    cpMediaBrowser
 * @subpackage model
 * @author     Coolpink <dev@coolpink.net>
 */
class cpMediaBrowserImageObject extends cpMediaBrowserFileObject
{
  protected $dimensions = null,
            $thumbnail = null
            ;

  
  public function __construct($file)
  {
    parent::__construct($file);
    if($this->getType() != 'image')
    {
      throw new sfException(sprintf('The file "%s" is not an image', $file));
    }
  }
  

  public function isImage()
  {
    return true;
  }
  
  
  public function getDimensions()
  {
    if(!$this->dimensions)
    {
      $this->dimensions = getimagesize($this->getPath());
    }
    return $this->dimensions;
  }

  public function getWidth()
  {
    $dimensions = $this->getDimensions();
    return $dimensions[0];
  }

  public function getHeight()
  {
    $dimensions = $this->getDimensions();
    return $dimensions[1];
  }
  
  
  public function getThumbnail()
  {
    if(!$this->thumbnail)
    {
      $this->thumbnail = new self($this->getUrlDir().'/'.sfconfig::get('app_cp_media_browser_thumbnails_dir').'/'.$this->getName(false).'_'.sfConfig::get('app_cp_media_browser_thumbnails_max_width', 90).'.'.$this->getExtension());
    }
    return file_exists($this->thumbnail->getPath())
           ? $this->thumbnail
           : null
           ;
  }
  
  
  public function getIcon()
  {
    $thumbnail = $this->getThumbnail();
    if($thumbnail)
    {
      return $thumbnail->getUrl();
    }
    return parent::getIcon();
  }
}
