<div id="cp_media_browser_forms">
  <div id="cp_media_browser_upload">
    <h2><?php echo __('Upload new asset') ?></h2>
    <a href="#close" class="closeForm"><img src="/cpCmsPlugin/images/icons-16/delete.png" alt="Close" title="Close" /></a>
    <form action="<?php echo url_for('cp_media_browser_file_create') ?>" method="post" enctype="multipart/form-data">
      <?php echo $upload_form ?>
      <div class="form-row">
        <span class="form-label"><label>&nbsp;</label></span>
        <div class="form-field"><input type="submit" class="submit" value="<?php echo __('Save') ?>" /></div>
      </div>
    </form>
  </div>

  <div id="cp_media_browser_mkdir">
    <h2><?php echo __('Create a new directory') ?></h2>
    <a href="#close" class="closeForm"><img src="/cpCmsPlugin/images/icons-16/delete.png" alt="Close" title="Close" /></a>
    <form action="<?php echo url_for('cp_media_browser_dir_create') ?>" method="post">
      <?php echo $dir_form ?>
      <div class="form-row">
        <span class="form-label"><label>&nbsp;</label></span>
        <div class="form-field"><input type="submit" class="submit" value="<?php echo __('Create') ?>" /></div>
      </div>
    </form>
  </div>
</div>