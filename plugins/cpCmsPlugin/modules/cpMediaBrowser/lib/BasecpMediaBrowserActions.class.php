<?php

/**
 *
 *
 * @package     cpMediaBrowser
 * @author      Coolpink <dev@coolpink.net>
 *
 * Note :
 * - ***_path = system directory
 * - ***_dir = browser directory
 */
class BasecpMediaBrowserActions extends sfActions
{
  protected $_thumb_sizes = array(90, 400);
  protected $_name = null;
  protected $_type = 'file';

  public function preExecute()
  {
    // symfony web path
    $this->web_path = sfConfig::get('sf_web_dir');

    // Configured root dir
    $this->root_dir = sfconfig::get('app_cp_media_browser_root_dir');

    $this->root_path = realpath($this->web_path.'/'.$this->root_dir);

    if (!in_array(sfConfig::get('app_cp_media_browser_thumbnails_max_width', 90), $this->_thumb_sizes))
    {
      $this->_thumb_sizes[] = sfConfig::get('app_cp_media_browser_thumbnails_max_width', 90);
    }
  }


  public function executeList(sfWebRequest $request)
  {
    $this->widget = false;
    $this->getResponse()->addCacheControlHttpHeader('no-cache'); // Required for Chrome Frame (IE caches the pages)

    $requested_dir = urldecode($request->getParameter('dir'));
    $relative_dir = $this->isPathSecured($this->root_path, $this->web_path.'/'.$requested_dir)
      ? $requested_dir
      : $this->root_dir;

    // browser dir relative to sf_web_dir
    $this->relative_dir = $relative_dir;
    // User dispay dir
    $this->display_dir = preg_replace('`^('.$this->root_dir.')`', '', $relative_dir);
    // browser parent dir
    $this->parent_dir = $this->relative_dir != $this->root_dir ? dirname($this->relative_dir) : '';
    // system path for current dir
    $this->path = $this->web_path.$relative_dir;
    $this->_type = $request->getParameter('show') ? $request->getParameter('show') : $this->_type;
    if ($this->_type == 'image')
    {
      $this->_name = '/\.png|\.jpg|\.jpeg|\.gif$/i';
    }
    $this->type = $this->_type;

    // list of directory structure
    $this->structure = $this->getAllDirectories();
    // list of sub-directories in current dir
    $this->dirs = $this->getDirectories($this->path);
    // list of files in current dir
    $this->files = $this->getFiles($this->path);
    $this->current_route = $this->getContext()->getRouting()->getCurrentRouteName();
    $this->current_params = $request->getGetParameters();
    $this->crops_dir = sfConfig::get('app_cp_media_browser_crops_dir');

    // forms
    $this->upload_form = new cpMediaBrowserUploadForm(array('directory' => $relative_dir));
    $this->dir_form = new cpMediaBrowserDirectoryForm(array('directory' => $relative_dir));
  }

  public function executeGetTreeStructure(sfWebRequest $request)
  {
    $requested_dir = $request->getParameter('dir');
    if(!$request->isXmlHttpRequest() || empty($requested_dir))
    {
      // Eeek. Not a valid request!!
      $this->redirect($request->getReferer());
    }

    $relative_dir = $this->isPathSecured($this->root_path, $this->web_path.'/'.$requested_dir)
      ? $requested_dir
      : $this->root_dir;

    // browser dir relative to sf_web_dir
    $this->relative_dir = $relative_dir;
    // browser parent dir
    $this->parent_dir = $this->relative_dir != $this->root_dir ? dirname($this->relative_dir) : '';
    // system path for current dir
    $this->path = $this->web_path.$relative_dir;

    // list of directory structure
    $this->structure = $this->getAllDirectories();
    // list of files in current dir
    $this->current_params = $request->getGetParameters();

    $response = array('html' => $this->getPartial('getTreeStructure'));

    return $this->renderText(json_encode($response));
  }


  public function executeWidget(sfWebRequest $request)
  {
    $this->_type = $request->getParameter("type");
    if ($this->_type == 'image')
    {
      $this->_name = '/\.png|\.jpg|\.jpeg|\.gif$/i';
    }
    $this->widget = true;

    $this->type = $this->_type;
    $this->setLayout(dirname(__FILE__).'/../templates/popupLayout');
  }


  public function executeSelect(sfWebRequest $request)
  {
    $this->_type = $request->getParameter("type", "file");
    if ($this->_type == 'image')
    {
      $this->_name = '/\.png|\.jpg|\.jpeg|\.gif$/i';
    }
    $this->widget = true;
    $this->setLayout(dirname(__FILE__).'/../templates/popupLayout');
    $this->setTemplate('list');
    $this->executeList($request);
  }

  public function executeCrop(sfWebRequest $request)
  {
    // errrrrr
    require_once(dirname(__FILE__).'/../../../../cpFormsPlugin/lib/Image.php');
    $image_url = $request->getParameter("image_url");
    $image_url_full = sfConfig::get('sf_web_dir').$image_url;
    $new_url = dirname($image_url)."/".$request->getParameter("new_url");
    $new_url_full = sfConfig::get('sf_web_dir')."/".$new_url;
    $h = $request->getParameter("h");
    $w = $request->getParameter("w");
    $x = $request->getParameter("x");
    $x2 = $request->getParameter("x2");
    $y = $request->getParameter("y");
    $y2 = $request->getParameter("y2");
    $resized_width = $request->getParameter("resized_width");
    $resized_height = $request->getParameter("resized_height");

    Image::crop_image($image_url_full, $new_url_full, $x, $y, $x2, $y2, $w, $h, $resized_width);

    $hash = md5("whmmw450mh450f");
    $cache_filename = sfConfig::get("sf_cache_dir").'/images/' . $hash . '-' . str_replace("/", "-", $request->getParameter("new_url"));
    if (file_exists($cache_filename)){
      unlink($cache_filename);
    }

    // thumbnail
    if(sfConfig::get('app_cp_media_browser_thumbnails_enabled', false))
    {
      $name = cpMediaBrowserStringUtils::slugify(pathinfo($new_url_full, PATHINFO_FILENAME));
      $ext = pathinfo($new_url_full, PATHINFO_EXTENSION);
      $destination_dir = realpath(sfConfig::get('sf_web_dir').'/'.dirname($image_url));
      $this->generateThumbnail($new_url_full, $name, $ext, $destination_dir);
    }

    echo json_encode(array("new_url" => $new_url));
    exit;
  }

  public function executeDelete(sfWebRequest $request)
  {
    $files = $request->getParameter('files');
    if(!$request->isXmlHttpRequest() || empty($files))
    {
      // Eeek. Not a valid request!!
      $this->redirect($request->getReferer());
    }

    $deleted = array();
    foreach ($files as $file)
    {
      $item = new cpMediaBrowserFileObject(urldecode($file));
      if (is_dir($item->getPath()))
      {
        $deleted[] = $this->deleteDirectory($item);
      }
      else if (is_file($item->getPath()))
      {
        $deleted[] = $this->deleteFile(urldecode($file));
      }
    }

    sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
    $errors = $this->recursiveArraySearch($deleted, 'error');
    if (empty($errors))
    {
      // No errors
      $response = format_number_choice('[1]Successfully deleted 1 item.|(1,+Inf]Successfully deleted %d% items.', array('%d%' => count($files)), count($files));
      return $this->renderText(json_encode(array('status' => 'notice', 'message' => $response)));
    }

    // Errors occurred
    $response = array(format_number_choice('[1]There was a problem deleting the item:|(1,+Inf]There was a problem deleting %d% of the %f% items:',
      array('%d%' => count($errors), '%f%' => count($files)), count($files)));
    foreach ($errors as $error)
    {
      $response[] = $files[$error];
    }

    $response = array('status' => 'error', 'message' => $response);

    return $this->renderText(json_encode($response));
  }


  public function executeCreateDirectory(sfWebRequest $request)
  {
    $form = new cpMediaBrowserDirectoryForm();
    $form->bind($request->getParameter('directory'));
    if($form->isValid())
    {
      $real_path = realpath($this->web_path.'/'.$form->getValue('directory'));
      $full_name = $real_path.'/'.$form->getValue('name');
      $created = @mkdir($full_name);
      @chmod($full_name, 0777);
      $this->getUser()->setFlash($created ? 'notice' : 'error', 'directory.create');
    }
    $this->redirect($request->getReferer());
  }


  protected function deleteDirectory(cpMediaBrowserFileObject $dir)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
    if (!$this->isPathSecured($this->root_path, $dir->getPath()))
    {
      return array('status' => 'error', 'name' => $dir->getName(), 'message' => __('The directory was not deleted due to an invalid path.'));
    }
    $deleted = cpMediaBrowserUtils::deleteRecursive($dir->getPath());

    sfContext::getInstance()->getLogger()->notice($this->getUser()->getUsername() . ' deleted directory ' . $dir->getName());

    return array('status' => $deleted ? 'notice' : 'error',
      'name'    => $dir->getName(),
      'message' => $deleted ?
        __('The directory was successfully deleted.') :
        __('The directory was not deleted due to an error.'));
  }


  public function executeCreateFile(sfWebRequest $request)
  {
    $upload = $request->getParameter('upload');
    $form = new cpMediaBrowserUploadForm();
    $form->setUploadDir($upload['directory']);
    $form->bind($upload, $request->getFiles('upload'));
    if($form->isValid())
    {
      $post_files = $form->getValue('file');
      if (isset($post_files['name']))
      {
        // Must be a direct form submit
        $post_files = array($post_files);
      }
      foreach ($post_files as $post_file)
      {
        $filename = $post_file->getOriginalName();
        $name = cpMediaBrowserStringUtils::slugify(pathinfo($filename, PATHINFO_FILENAME));
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $fullname = $ext ? $name.'.'.$ext : $name;
        $destination_dir = realpath(sfConfig::get('sf_web_dir').'/'.$upload['directory']);
        // thumbnail
        if(sfConfig::get('app_cp_media_browser_thumbnails_enabled', false) && cpMediaBrowserUtils::getTypeFromExtension($ext) == 'image')
        {
          $this->generateThumbnail($post_file->getTempName(), $name, $ext, $destination_dir);
        }
        $post_file->save($destination_dir.'/'.$fullname);
      }

      $this->getUser()->setFlash('uploaded_success', $this->getUser()->getFlash('uploaded_success', 0) + count($post_files));
      if($request->isXmlHttpRequest()) $response = array('status' => 'success', 'message' => 'All uploaded!');
    }
    else
    {
      $this->getUser()->setFlash('uploaded_error', $this->getUser()->getFlash('uploaded_error', 0) + count($post_files));
      if($request->isXmlHttpRequest()) $response = array('status' => 'error', 'message' => 'Some files failed!');
    }

    if($request->isXmlHttpRequest())
    {
      $response['filename'] = $fullname;
      $response['directory'] = $upload['directory'];

      return $this->renderText(json_encode($response));
    }
    else
    {
      $this->redirect($request->getReferer());
    }
  }


  /**
   * @param array $requests an array of sfWebRequest's
   * @return
   */
  public function executeMove(sfWebRequest $request)
  {
    $data = $request->getParameter('data');
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
    $response = array('status' => 'notice', 'message' => '');
    $_response = array();

    foreach ($data as $item)
    {
      $file = new cpMediaBrowserFileObject($item['file']);
      $dir = new cpMediaBrowserFileObject($item['dir']);
      $new_name = $dir->getPath().'/'.$file->getName(true);
      $error = null;
      try
      {
        $moved = @rename($file->getPath(), $new_name);

        // thumbnail
        if(sfConfig::get('app_cp_media_browser_thumbnails_enabled', false) && $file->getType() == 'image')
        {
          foreach ($this->_thumb_sizes as $size)
          {
            // Size 400 is the cropped image
            $thumb = ($size == 400) ? $file->getRootPath().$file->getUrlDir().'/'.sfConfig::get('app_cp_media_browser_crops_dir').'/'.$file->getName() :
              $file->getRootPath().$file->getUrlDir().'/'.sfConfig::get('app_cp_media_browser_thumbnails_dir').'/'.$file->getName(false).'_'.$size.'.'.$file->getExtension();
            if (file_exists($thumb))
            {
              $destination_dir = ($size == 400) ? $dir->getPath().'/'.sfConfig::get('app_cp_media_browser_crops_dir') :
                $dir->getPath().'/'.sfConfig::get('app_cp_media_browser_thumbnails_dir');
              if(!file_exists($destination_dir))
              {
                mkdir($destination_dir);
                chmod($destination_dir, 0777);
              }
              $thumbnail = ($size == 400) ? $file->getName() : $file->getName(false).'_'.$size.'.'.$file->getExtension();
              @rename($thumb, $destination_dir.'/'.$thumbnail);
            }
          }
        }
      }
      catch(Exception $e)
      {
        $error = $e;
      }

      if($request->isXmlHttpRequest())
      {
        if($error)
        {
          $response['status'] = 'error';
          $_response[$file->getUrl()] = array('status' => 'error', 'message' => __('Some error occured.'));
        }
        elseif($moved)
        {
          $_response[$new_name] = array('status' => 'notice', 'message' => __('The file was successfully moved.'));
        }
        elseif(file_exists($new_name))
        {
          $response['status'] = 'error';
          $_response[$file->getUrl()] = array('status' => 'error', 'message' => __('A file with the same name already exists in this folder.'));
        }
        else
        {
          $response['status'] = 'error';
          $_response[$file->getUrl()] = array('status' => 'error', 'message' => __('Some error occured.'));
        }
      }
    }

    if($request->isXmlHttpRequest())
    {
      if ($response['status'] == 'error')
      {
        $failed = 0;
        $messages = array();
        foreach ($_response as $path => $details)
        {
          if ($details['status'] == 'error')
          {
            $failed++;
            $messages[$path] = $details['message'];
          }
        }

        $response['message'] = __('Not all files moved successfully. The following error/s occurred:');
        $response['message'] .= '<ul>';
        foreach ($messages as $path => $message)
        {
          $response['message'] .= "<li>{$path} -> {$message}</li>";
        }
        $response['message'] .= '</ul>';
      }
      else
      {
        $response['message'] = format_number_choice('[0,1]The file was successfully moved.|(1,+Inf]%d% files were successfully moved.',
          array('%d%' => count($_response)), count($_response));
      }

      return $this->renderText(json_encode($response));
    }

    $this->redirect($request->getReferer());
  }


  public function executeRename(sfWebRequest $request)
  {
    $file = new cpMediaBrowserFileObject($request->getParameter('file'));
    $name = cpMediaBrowserStringUtils::slugify(pathinfo($request->getParameter('name'), PATHINFO_FILENAME));
    $ext = $file->getExtension();
    $valid_filename = $ext ? $name.'.'.$ext : $name;
    $new_name = dirname($file->getPath()).'/'.$valid_filename;

    $error = null;
    try
    {
      // thumbnail
      if(sfConfig::get('app_cp_media_browser_thumbnails_enabled', false) && $file->getType() == 'image')
      {
        foreach ($this->_thumb_sizes as $size)
        {
          // Size 400 is the cropped image
          $thumb = ($size == 400) ? $file->getRootPath().$file->getUrlDir().'/'.sfConfig::get('app_cp_media_browser_crops_dir').'/'.$file->getName() :
            $file->getRootPath().$file->getUrlDir().'/'.sfConfig::get('app_cp_media_browser_thumbnails_dir').'/'.$file->getName(false).'_'.$size.'.'.$file->getExtension();
          if (file_exists($thumb))
          {
            $thumbnail = ($size == 400) ? sfConfig::get('app_cp_media_browser_crops_dir').'/'.$name.'.'.$ext :
              sfConfig::get('app_cp_media_browser_thumbnails_dir').'/'.$name.'_'.$size.'.'.$ext;
            @rename($thumb, dirname($file->getPath()).'/'.$thumbnail);
          }
        }
      }
      $renamed = @rename($file->getPath(), $new_name);
    }
    catch(Exception $e)
    {
      $error = $e;
    }

    if($request->isXmlHttpRequest())
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
      if($error)
      {
        $reponse = array('status' => 'error', 'message' => __('Some error occured.'));
      }
      elseif($renamed)
      {
        $response = array('status' => 'notice', 'message' => __('The file was successfully renamed.'), 'name' => $valid_filename, 'url' => dirname($file->getUrl()).'/'.$valid_filename);
      }
      elseif(file_exists($new_name))
      {
        $response = array('status' => 'error', 'message' => __('A file with the same name already exists in this folder.'));
      }
      else
      {
        $response = array('status' => 'error', 'message' => __('Some error occured.'));
      }
      return $this->renderText(json_encode($response));
    }
    $this->redirect($request->getReferer());
  }


  /**
   * @TODO
   * @param $file
   */
  protected function generateThumbnail($source_file, $destination_name, $ext, $destination_dir)
  {
    if(!class_exists('sfImage'))
    {
      throw new sfException('sfImageTransformPlugin must be installed in order to generate thumbnails.');
    }

    $result = true;
    $thumb_dir = $destination_dir.'/'.sfConfig::get('app_cp_media_browser_thumbnails_dir');
    $crop_dir = $destination_dir.'/'.sfConfig::get('app_cp_media_browser_crops_dir');
    foreach ($this->_thumb_sizes as $size)
    {
      if ($size == 400)
      {
        // Create special crop image
        $result = $this->saveImage($source_file, $crop_dir, $destination_name.'.'.$ext, $size);
      }
      else
      {
        $result = $this->saveImage($source_file, $thumb_dir, $destination_name.'_'.$size.'.'.$ext, $size);
      }
    }

    return $result;
  }

  protected function saveImage($source, $dir, $name, $size)
  {
    if(!file_exists($dir))
    {
      mkdir($dir);
      chmod($dir, 0777);
    }
    $thumb = new sfImage($source);
    $thumb->thumbnail($size, $size, 'scale');

    return $thumb->saveAs($dir.'/'.$name);
  }


  protected function deleteFile($file)
  {
    $file = $this->createFileObject($file);
    // thumbnail
    if(sfConfig::get('app_cp_media_browser_thumbnails_enabled', false) && $file->getType() == 'image')
    {
      foreach ($this->_thumb_sizes as $size)
      {
        // Size 400 is the cropped image
        $thumb = ($size == 400) ? $file->getUrlDir().'/'.sfConfig::get('app_cp_media_browser_crops_dir').'/'.$file->getName() :
          $file->getUrlDir().'/'.sfConfig::get('app_cp_media_browser_thumbnails_dir').'/'.$file->getName(false).'_'.$size.'.'.$file->getExtension();

        $thumb = $this->createFileObject($thumb);
        $thumb->delete();
      }
    }
    $deleted = $file->delete();

    sfContext::getInstance()->getLogger()->notice($this->getUser()->getUsername() . ' deleted file ' . $file->getName());

    sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
    return array('status' => $deleted ? 'notice' : 'error',
      'name'    => $file->getName(),
      'message' => $deleted ?
        __('The file was successfully deleted.') :
        __('The file was not deleted due to an error.'));
  }


# Protected

  protected function createFileObject($file)
  {
    $class = cpMediaBrowserUtils::getTypeFromExtension(pathinfo($file, PATHINFO_EXTENSION)) == 'image'
      ? 'cpMediaBrowserImageObject'
      : 'cpMediaBrowserFileObject'
    ;
    return new $class($file);
  }


  /**
   *
   * @param string $root_path
   * @param string $dir
   * @return mixed <string, boolean>
   */
  protected function isPathSecured($root_path, $requested_path)
  {
    return preg_match('`^'.preg_quote(realpath($root_path)).'`', realpath($requested_path));
  }


  protected function getDirectories($path)
  {
    return sfFinder::type('dir')->
      maxdepth(0)->
      prune('.*')->
      discard('.*')->
      discard(sfConfig::get('app_cp_media_browser_thumbnails_dir'), sfConfig::get('app_cp_media_browser_crops_dir'))->
      sort_by_name()->
      relative()->
      in($path)
      ;
  }


  protected function getAllDirectories($excludes = array())
  {
    $ignores = array('.svn', '_svn', 'CVS', '_darcs', '.arch-params', '.monotone', '.bzr', '.git', '.hg', sfConfig::get('app_cp_media_browser_thumbnails_dir'), sfConfig::get('app_cp_media_browser_crops_dir'));
    $ignores = array_merge($ignores, $excludes);

    return $this->fillArrayWithFileNodes(new DirectoryIterator($this->web_path . $this->root_dir), $ignores);
  }

  protected function fillArrayWithFileNodes(DirectoryIterator $dir, $exclude = array())
  {
    $data = array();
    foreach ( $dir as $node )
    {
      if ( $node->isDir() && !$node->isDot() && substr($node->getFilename(), 0, 1) !== '.' && !in_array($node->getFilename(), $exclude) )
      {
        $data[$node->getFilename()] = $this->fillArrayWithFileNodes( new DirectoryIterator( $node->getPathname() ), $exclude );
      }
    }

    return $data;
  }


  protected function getFiles($path)
  {
    if ($this->_name)
    {
      return sfFinder::type('file')->
        name($this->_name)->
        maxdepth(0)->
        prune('.*')->
        discard('.*')->
        relative()->
        sort_by_name()->
        in($path)
        ;
    }

    return sfFinder::type('file')->
      maxdepth(0)->
      prune('.*')->
      discard('.*')->
      relative()->
      sort_by_name()->
      in($path)
      ;
  }

  /**
   * Searches recursively through an array for a value
   *
   * @param  $haystack The array
   * @param  $needle   The searched value
   * @return array of keys where $needle is found
   */
  protected function recursiveArraySearch($haystack, $needle)
  {
    $locs = array();
    $aIt = new RecursiveArrayIterator($haystack);
    $it = new RecursiveIteratorIterator($aIt);

    while($it->valid())
    {
      if ($it->current() == $needle) {
        $locs[] = $aIt->key();
      }

      $it->next();
    }

    return $locs;
  }

}
