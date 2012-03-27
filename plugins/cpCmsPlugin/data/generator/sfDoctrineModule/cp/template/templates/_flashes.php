<script type="text/javascript">
  $(function (){
    [?php if ($sf_user->hasFlash('notice')): ?]
      $.cpbar({
        message  : "[?php echo addslashes(__($sf_user->getFlash('notice'), array(), 'sf_admin')) ?]",
        type   : "success"
      });
    [?php endif; ?]

    [?php if ($sf_user->hasFlash('error')): ?]
      $.cpbar({
        message  : "[?php echo addslashes(__($sf_user->getFlash('error'), array(), 'sf_admin')) ?]",
        type   : "error"
      });
    [?php endif; ?]
  });
</script>