<div class="icon" title="<?php echo $file->getName() . ' (' . $file->getSize() . ')' ?>" style="background-image: url(<?php echo $file->getIcon() ?>)">
</div>
<label class="name"><?php echo $file->getShortName() ?></label>
<div class="info">
  <span class="fullname"><?php echo $file->getName() ?></span>
  <span class="size"><?php echo $file->getSize() ?></span>
  <span class="type"><?php echo ucfirst($file->getType()) ?></span>
  <?php if($file->isImage()): ?>
  <span class="dimensions"><?php echo $file->getWidth() ?> x <?php echo $file->getHeight() ?></span>
  <?php endif ?>
</div>
<div class="action">
  <span class="link"><?php echo link_to(' ', $file->getUrl()) ?></span>
  <ul class="is-context-menu" id="mb_action_<?php echo preg_replace('/[^\w\d\-\_]/', '', $file->getName()) ?>">
    <li class="rename"><?php echo link_to('Rename', 'cp_media_browser_file_rename', array('file' => urlencode($file->getUrl())),
                array('class' => 'rename', 'title' => sprintf("Rename file '%s'", $file->getName()))
            ) ?></li>
    <li class="delete"><?php echo link_to('Delete', 'cp_media_browser_delete', array('file' => urlencode($file->getUrl())),
                array('class' => 'delete', 'title' => sprintf("Delete file '%s'", $file->getName()))
            ) ?></li>
  </ul>
</div>
