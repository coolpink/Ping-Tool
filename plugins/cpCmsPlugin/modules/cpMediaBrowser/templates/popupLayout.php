<?php
// global
$assets = sfConfig::get('app_cpAdminMenu_assets');
cpMediaBrowserUtils::loadAssets($assets['global'], null, 'first');
// listings
cpMediaBrowserUtils::loadAssets($assets['listings'], null, 'first');
// actions bar
cpMediaBrowserUtils::loadAssets($assets['actions'], null, 'first');
// cp bar
cpMediaBrowserUtils::loadAssets($assets['cpBar'], null, 'first');
// wizard
cpMediaBrowserUtils::loadAssets($assets['wizard'], null, 'first');
// form
cpMediaBrowserUtils::loadAssets($assets['form'], null, 'first');
// context menu
cpMediaBrowserUtils::loadAssets($assets['contextMenu'], null, 'first');

// media browser widget
cpMediaBrowserUtils::loadAssets(sfConfig::get('app_cp_media_browser_assets_widget'))
?>
<!DOCTYPE html>
<html lang="en-gb">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <script type="text/javascript">
      $(function (){
        $('body').layout({ applyDefaultStyles: false, spacing_open: 0, west__size: 250});
      });
    </script>
  </head>
  <body class="widget">
    <?php echo $sf_content ?>
  </body>
</html>
