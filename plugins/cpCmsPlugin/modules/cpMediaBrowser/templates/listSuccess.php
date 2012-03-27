<?php use_helper('I18N') ?>
<?php cpMediaBrowserUtils::loadAssets(sfConfig::get('app_cp_media_browser_assets_list')) ?>

<script type="text/javascript">
  $(function (){
    myLayout = $('#main-center').layout({ applyDefaultStyles: false, spacing_open: 0 }).allowOverflow('north');
  });
</script>

<div class="ui-layout-center" id="main-center">

  <div class="ui-layout-north">

    <?php include_partial('fileactions') ?>
    <?php include_partial('forms', array('upload_form' => $upload_form, 'dir_form' => $dir_form)) ?>

    <h1>
      <?php echo __('Current location', array(), 'assets') ?>
      <span class="location"><?php echo $display_dir ? $display_dir : '/' ?></span>
    </h1>

  </div>

  <div class="ui-layout-center scrollable" id="page">

    <?php include_partial('filelist', array(
      'parent_dir' => $parent_dir,
      'relative_dir' => $relative_dir,
      'current_route' => $current_route,
      'sf_data' => $sf_data,
      'dirs' => $dirs,
      'files' => $files,
      'type' => $type,
    )) ?>

  </div>

  <div class="ui-layout-south">

    <?php include_partial('notices', array('sf_user' => $sf_user)) ?>
      
    <?php include_partial('scripts', array('relative_dir' => $relative_dir, 'crops_dir' => $crops_dir)) ?>

  </div>

</div>

<?php if($widget): ?>
<div class="sidebar scrollable metabar ui-layout-west">

  <?php
  include_partial('sidebar', array(
    'structure' => $structure,
    'root_dir' => $root_dir,
    'relative_dir' => $relative_dir,
    'current_params' => $sf_data->getRaw('current_params'),
  ));
  ?>

</div>
<?php endif; ?>