<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-gb" >
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
	<script type="text/javascript">
	$(function (){
		$('form').loginForm([
			{
				color: '#000',
				image: '/cpCmsPlugin/images/backgrounds/bg.jpg'
			},
			{
				color: '#0c3f5e',
        image: '/cpCmsPlugin/images/backgrounds/bg2.jpg'
			},
			{
				color: '#200100',
        image: '/cpCmsPlugin/images/backgrounds/bg3.jpg'
			},
			{
				color: '#242424',
        image: '/cpCmsPlugin/images/backgrounds/bg4.jpg'
			}/*,
			{
				color: '#ecf5fd',
				image: 'login/images/backgrounds/bg5.jpg'
			}*/,
			{
				color: '#652e18',
        image: '/cpCmsPlugin/images/backgrounds/bg6.jpg'
			},
			{
				color: '#000',
        image: '/cpCmsPlugin/images/backgrounds/bg7.jpg'
			}
		]);
	});
	</script>
  </head>
  <body>
    <?php echo $sf_content ?>
    <div class="footer">
		<a class="splash" href="http://www.coolpink.net" title="Made by Coolpink">Coolpink</a>
	</div>
  </body>
</html>
