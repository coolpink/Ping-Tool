<?php

require_once dirname(__FILE__)."/Image.php";

try {

    $filename = $_GET['filename'];
    $tmp_args = explode('/', $_GET['args']);
    $args = array();

    // parse the args string into an array
    for ($i = 0, $m = sizeof($tmp_args); $i < $m; $i += 2) {
        if (isset($tmp_args[$i + 1])) {
            $args[$tmp_args[$i]] = intval($tmp_args[$i + 1]);
            if (strtolower($tmp_args[$i]) == 'fit') {
                $args[$tmp_args[$i]] = strtoupper($tmp_args[$i + 1]);
            }
        }
  }

  if (empty($args))
  {
    unset($_GET["args"]);
    $args = $_GET;
  }

    $width = isset($args['width']) ? $args['width'] : null;
    $height = isset($args['height']) ? $args['height'] : null;
    $max = isset($args['max']) ? $args['max'] : null;
    $max_width = isset($args['max_width']) ? $args['max_width'] : null;
    $max_height = isset($args['max_height']) ? $args['max_height'] : null;
    $fit = isset($args['fit']) ? $args['fit'] : null;
    $radius = isset($args['rounded']) ? $args['rounded'] : null;

    $full_filename = "uploads/".$filename;


    // the cache file
    $hash = md5("w{$width}h{$height}m{$max}mw{$max_width}mh{$max_height}f{$fit}");

    if (!file_exists(sfConfig::get("sf_cache_dir").'/images/'))
    {
        mkdir(sfConfig::get("sf_cache_dir").'/images/');
        chmod(sfConfig::get("sf_cache_dir").'/images/', 0777);
    }


    $cache_filename = sfConfig::get("sf_cache_dir").'/images/' . $hash . '-' . str_replace("/", "-", $filename);


    $extpos = strrpos($filename, ".");
    $ext = substr($filename, $extpos+1);
    $mime_types = array(
        "jpg" => "image/jpeg",
        "png" => "image/png",
        "jpeg" => "image/jpeg",
        "gif" => "image/gif",
        "bmp" => "image/bmp",
    );
    $ext = strtolower($ext);
    $mime_type = $mime_types[$ext];


    if (file_exists($cache_filename)) {

        $last_modified = filemtime($cache_filename);  // always need this, even if we don't serve the whole image

        if (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"]) && strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]) >= $last_modified) {
            // sweet as, don't need to send image. phew!
            header("HTTP/1.1 304 Not Modified");
            exit();
        }

        // we only get this far if browser doesn't have image cached, or it's old

        if($radius) {
            header('Content-Type: image/png');
        } else {
            $type = $asset ? $asset->mime_type : $mime_type;
            header('Content-Type: ' . $type);
        }

        header("Connection: close");
        // this breaks re-sized images in chrome (known chrome issue)
        //header("Content-Length: " . filesize($cache_filename));
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", $last_modified) . " GMT");
        readfile($cache_filename);
        exit();

    } else {

        // generate the cache

        if (!file_exists($full_filename)) {
            throw new Exception('The requested file does not exist');
        }
        $success = false;
        if ($width && $height) {
            if (!$fit) {
                $success = Image::resize_crop($full_filename, $cache_filename, $width, $height);
            } else {
                $success = Image::resize_fit($full_filename, $cache_filename, $width, $height, $fit);
            }
        } elseif ($width && !$height) {
            $success = Image::resize_w($full_filename, $cache_filename, $width);
        } elseif (!$width && $height) {
            $success = Image::resize_h($full_filename, $cache_filename, $height);
        } elseif ($max) {
            $success = Image::resize_max($full_filename, $cache_filename, $max);
        } elseif ($max_width || $max_height){
            $success = Image::resize_maxsize($full_filename, $cache_filename, $max_width, $max_height);
        }

        if($radius)
        {
            if (file_exists($cache_filename)) {
                $filename = $cache_filename;
            }   else {
                $filename = $full_filename;
            }

            $success = Image::roundcorners($filename, $cache_filename, $radius);
        }

        if (!$success) {
            throw new Exception('The image could not be resized');
        }

        if($radius) {
            header('Content-Type: image/png');
        } else {
            $type = $asset ? $asset->mime_type : $mime_type;
            header('Content-Type: ' . $type);
        }

        $last_modified = filemtime($cache_filename);  // always need this, even if we don't serve the whole image

        header("Connection: close");
        // this breaks re-sized images in chrome (known chrome issue)
        //header("Content-Length: " . filesize($cache_filename));
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", $last_modified) . " GMT");
        readfile($cache_filename);
        exit();

    }

} catch (Exception $e) {

    echo $e->getMessage();

}
