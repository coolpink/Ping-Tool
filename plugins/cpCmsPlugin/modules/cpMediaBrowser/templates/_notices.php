<?php if($sf_user->hasFlash('error')): ?>
  <script type="text/javascript">
    $(function (){
      $.cpbar({
        message : '<?php if($sf_user->getFlash('error') == 'directory.delete'):
                    echo __('The directory could not be deleted.');
                  elseif($sf_user->getFlash('error') == 'directory.create'):
                    echo __('The directory could not be created.');
                  endif ?>',
        type    : 'error',
        id      : 'asset_notifications'
      });
    });
  </script>
<?php elseif($sf_user->hasFlash('notice')): ?>
  <script type="text/javascript">
    $(function (){
      $.cpbar({
        message : '<?php if($sf_user->getFlash('notice') == 'directory.create'):
                    echo __('The directory was successfully created.');
                  elseif($sf_user->getFlash('notice') == 'directory.delete'):
                    echo __('The directory was successfully deleted.');
                  endif ?>',
        type    : 'success',
        id      : 'asset_notifications'
      });
    });
  </script>
<?php endif; ?>

<?php if($sf_user->hasFlash('uploaded_success') || $sf_user->hasFlash('uploaded_error')): ?>
  <?php
      $uploaded_success = $sf_user->getFlash('uploaded_success', 0);
      $uploaded_error = $sf_user->getFlash('uploaded_error', 0);
      if ($uploaded_success && !$uploaded_error):
        $notice_status = 'success';
        $notice_message = ($uploaded_success > 1) ?
            sprintf(__('Successfully uploaded %d files.'), $uploaded_success) :
            sprintf(__('Successfully uploaded %d file.'), $uploaded_success);
      elseif ($uploaded_success && !$uploaded_error):
        $notice_status = 'error';
        $notice_message = $uploaded_error > 1 ?
            sprintf(__('%d files failed to upload.'), $uploaded_success) :
            sprintf(__('%d file failed to upload.'), $uploaded_error);
      else:
        $notice_status = 'info';
        if (($uploaded_success > 1 || $uploaded_success == 0) && ($uploaded_error > 1 || $uploaded_error == 0)):
          $notice_message = sprintf(__('Successfully uploaded %d files.'), $uploaded_success) .
                      ' ' . sprintf(__('%d files failed to upload.'), $uploaded_error);
        elseif ($uploaded_success == 1 && ($uploaded_error > 1 || $uploaded_error == 0)):
          $notice_message = sprintf(__('Successfully uploaded %d file.'), $uploaded_success) .
                      ' ' . sprintf(__('%d files failed to upload.'), $uploaded_error);
        elseif (($uploaded_success > 1 || $uploaded_success == 0) && $uploaded_error == 1):
          $notice_message = sprintf(__('Successfully uploaded %d files.'), $uploaded_success) .
                      ' ' . sprintf(__('%d file failed to upload.'), $uploaded_error);
        else:
          $notice_message = sprintf(__('Successfully uploaded %d file.'), $uploaded_success) .
                      ' ' . sprintf(__('%d file failed to upload.'), $uploaded_error);
        endif;
      endif;
  ?>
  <script type="text/javascript">
    $(function (){
      $.cpbar({
        message : '<?php echo $notice_message ?>',
        type    : '<?php echo $notice_status ?>',
        id      : 'asset_notifications'
      });
    });
  </script>
<?php endif; ?>