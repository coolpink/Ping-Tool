<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	  <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <?php include_stylesheets() ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script type="text/javascript">
    var developmentMode = true;
  </script>
  <link rel="stylesheet/less" type="text/css" href="css/master.less">
  <?php include_javascripts() ?>

  <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <?php if (sfConfig::get('sf_environment') != "prod"): ?>
  <meta name="robots" content="noindex" />
  <?php endif; ?>

  </head>
  <body>
    <div class="container-fluid">
      <div class="row-fluid">
        <div id="logo"><h1><a href="/"><span>Coolpink</span></a></h1></div>
      </div>
      
      <?php echo $sf_content ?>

      <div class="row-fluid">
        <hr />
        <footer>
          <p>&copy; Coolpink <?php echo date("Y"); ?></p>
        </footer>
      </div>
    </div>
  </body>
</html>