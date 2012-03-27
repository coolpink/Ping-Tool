<ul id="cp_media_browser_list">

  <?php if($parent_dir): ?>
  <li class="up" title="Parent directory">
    <div class="icon">
      <?php echo link_to(' ', $current_route,
            array_merge($sf_data->getRaw('current_params'), array('dir' => $parent_dir, 'show' => $type)),
            array('title' => 'Parent directory')) ?>
    </div>
    <label class="name">Parent directory</label>
    <div class="action">
      <span class="link"><?php echo link_to(' ', $current_route,
            array_merge($sf_data->getRaw('current_params'), array('dir' => $parent_dir, 'show' => $type)),
            array('title' => 'Parent directory')) ?></span>
    </div>
  </li>
  <?php endif ?>

<?php foreach($dirs as $dir): ?>
  <li class="folder selectable-cp" title="<?php echo $dir ?>">
    <div class="icon" title="<?php echo $dir ?>">
    </div>
    <label class="name"><?php echo $dir ?></label>
    <span class="fullname"><?php echo $dir ?></span>
    <span class="type">Directory</span>
    <div class="action">
      <span class="link"><?php echo link_to(' ', $current_route,
            array_merge($sf_data->getRaw('current_params'), array('dir' => $relative_dir.'/'.$dir, 'show' => $type))) ?></span>
      <ul class="is-context-menu" id="mb_action_<?php echo preg_replace('/[^\w\d\-\_]/', '', $dir) ?>">
        <li class="rename">
          <?php echo link_to('Rename', 'cp_media_browser_dir_rename',
                  array('sf_method' => 'rename', 'directory' => urlencode($relative_dir.'/'.$dir)),
                  array('class' => 'rename', 'title' => sprintf(__("Rename folder '%s'"), $dir))) ?>
        </li>
        <li class="delete">
          <?php echo link_to('Delete', 'cp_media_browser_delete',
                  array('sf_method' => 'delete', 'directory' => urlencode($relative_dir.'/'.$dir)),
                  array('class' => 'delete', 'title' => sprintf(__("Delete folder '%s'"), $dir))) ?>
        </li>
      </ul>
    </div>
  </li>
<?php endforeach ?>

<?php foreach($files as $file): ?>
  <li class="file selectable-cp" title="<?php echo $file ?>">
    <?php include_component('cpMediaBrowser', 'icon', array('file_url' => $relative_dir.'/'.$file)) ?>
  </li>
<?php endforeach ?>
</ul>