<?php
// global
$assets = sfConfig::get('app_cpAdminMenu_assets');
cpMediaBrowserUtils::loadAssets($assets['global'], null, 'first');
// listings
cpMediaBrowserUtils::loadAssets($assets['listings'], null, 'first');
// actions bar
cpMediaBrowserUtils::loadAssets($assets['actions'], null, 'first');
// adminMenu
cpMediaBrowserUtils::loadAssets($assets['adminMenu'], null, 'first');
// cp bar
cpMediaBrowserUtils::loadAssets($assets['cpBar'], null, 'first');
// wizard
cpMediaBrowserUtils::loadAssets($assets['wizard'], null, 'first');
// cropper
cpMediaBrowserUtils::loadAssets($assets['cropper'], null, 'first');
// form
cpMediaBrowserUtils::loadAssets($assets['form'], null, 'first');
// tinymce
cpMediaBrowserUtils::loadAssets($assets['tinymce'], null, 'first');
// context menu
cpMediaBrowserUtils::loadAssets($assets['contextMenu'], null, 'first');
?>
<!DOCTYPE html>
<html lang="en-gb">
  <head>
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <script type="text/javascript">
      $(function (){
        $('#admin-menu').adminMenu();
        $('body').layout({ applyDefaultStyles: false, spacing_open: 0, west__size: 250});
      });
    </script>
    <!--[if IE]>
      <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
      <script type="text/javascript" src="/cpCmsPlugin/libraries/chromeframe.js"></script>
    <![endif]-->
  </head>
  <body>
   <div class="ui-layout-north" id="admin-menu">
    <?php include_component('cpAdminMenu', 'menu') ?>
   </div>
   <?php echo $sf_content ?>
  </body>
</html>
