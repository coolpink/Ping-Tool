<ul id="cp_media_browser_actions" class="actions-bar">
  <li class="sf_admin_action_new">
    <a href="#uploadFile" id="cp_media_browser_upload_file">
      <span>
        <img src="/cpCmsPlugin/images/icons-16/script_add.png" alt="" />
        <?php echo __('Upload new asset', array(), 'assets') ?>
      </span>
    </a>
  </li>
  <li class="sf_admin_action_new">
    <a href="#createDir" id="cp_media_browser_create_dir">
      <span>
        <img src="/cpCmsPlugin/images/icons-16/folder_add.png" alt="" />
        <?php echo __('Create new directory', array(), 'assets') ?>
      </span>
    </a>
  </li>
  <li class="spacer">&nbsp;</li>
  <li class="sf_admin_action_select">
    <a href="#select" id="cp_media_browser_select">
      <span>
        <img src="/cpCmsPlugin/images/icons-16/accept.png" alt="" />
        <?php echo __('Use file', array(), 'assets') ?>
      </span>
    </a>
  </li>
  <li class="sf_admin_action_rename">
    <a href="#rename" id="cp_media_browser_rename">
      <span>
        <img src="/cpCmsPlugin/images/icons-16/script_edit.png" alt="" />
        <?php echo __('Rename', array(), 'assets') ?>
      </span>
    </a>
  </li>
  <li class="sf_admin_action_delete">
    <a href="#delete" id="cp_media_browser_delete">
      <span>
        <img src="/cpCmsPlugin/images/icons-16/delete.png" alt="" />
        <?php echo __('Delete', array(), 'assets') ?>
      </span>
    </a>
  </li>
  <li class="sf_admin_action_style">
    <a href="#style" id="cp_media_browser_style">
      <span>
        <img src="/cpCmsPlugin/images/icons-16/image_resize.png" alt="" />
        <?php echo __('Change display style', array(), 'assets') ?>
        <img src="/cpCmsPlugin/images/icons-16/down_arrow.png" alt="" />
      </span>
    </a>
    <ul id="sf_admin_action_change_style">
      <li class="list">
        <a>
          <span>
            <img src="/cpCmsPlugin/images/icons-16/list.png" alt="" />
            <?php echo __('List', array(), 'assets') ?>
          </span>
        </a>
      </li>
      <li class="grid">
        <a>
          <span>
            <img src="/cpCmsPlugin/images/icons-16/medium_icons.png" alt="" />
            <?php echo __('Grid', array(), 'assets') ?>
          </span>
        </a>
      </li>
    </ul>
  </li>
</ul>