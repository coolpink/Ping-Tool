<div id="cp_media_browser_structure">
  <?php include_component('cpMediaBrowser', 'tree', array('structure' => $structure,
        'root_dir' => $root_dir,
        'relative_dir' => $relative_dir,
        'current_params' => $current_params,
  )) ?>
</div>

<div id="cp_media_browser_file_info">
  <h4>File information</h4>
  <div class="name"></div>
  <div class="type">Type: <span></span></div>
  <div class="size">Size: <span></span></div>
  <div class="dimensions">Dimensions: <span></span></div>
</div>