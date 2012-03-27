<script type="text/javascript">
var delete_msg_dir = '<?php echo __('Are you sure you want to delete the directory "%s%" and all files in it?') ?>';
var delete_msg_file = '<?php echo __('Are you sure you want to delete the file "%s%"?') ?>';
var delete_msg_many_file = '<?php echo __('Are you sure you want to delete these %d% files?') ?>';
var delete_msg_many_file_dir = '<?php echo __('Are you sure you want to delete these %f% files, including %d% directory (and all files in it)?') ?>';
var delete_msg_many_file_dirs = '<?php echo __('Are you sure you want to delete these %f% files, including %d% directories (and all files in them)?') ?>';
var delete_msg_many_dir = '<?php echo __('Are you sure you want to delete %d% directories and all files in them?') ?>';
var rename_msg = '<?php echo __('Enter the new file name.') ?>';
var move_file_url = '<?php echo url_for('cp_media_browser_move') ?>';
var rename_file_url = '<?php echo url_for('cp_media_browser_rename') ?>';
var delete_url = '<?php echo url_for('cp_media_browser_delete') ?>';
var tree_url = '<?php echo url_for('cp_media_browser_tree_structure') ?>';
var dir_url = '<?php echo $relative_dir ?>';
var crop_url = '<?php echo $crops_dir ?>';
var upload_files = [];
var hover_folder = $();
$('#cp_media_browser_list').styleSwitcher({selector: '#cp_media_browser_actions li.sf_admin_action_style > a'});
$('#page').filedrop({
    fallback: '#cp_media_browser_upload form', // jQuery selector to standard form
    targets: '#cp_media_browser_list li.ui-droppable', // jQuery selector to targets
    url: '<?php echo url_for('cp_media_browser_file_create') ?>',
    paramname: 'upload[file]',
    data: {
        'upload[directory]': $('#upload_directory').val(),
        'upload[_csrf_token]': $('#upload__csrf_token').val()
    },
    error: function(err, file) {
        switch(err) {
            case 'BrowserNotSupported':
                $.cpbar({
                  message : 'The browser you are using does not support drag and drop file uploads.' +
                            ' Please use the standard "Upload new asset" form or consider upgrading your browser to one that does.',
                  type    : 'error',
                  id      : 'asset_notifications'
                });
                break;
            case 'TooManyFiles':
                $.cpbar({
                  message : 'You cannot upload more than ' + this.maxfiles + ' files at a time.' +
                            ' Please upload the files in lots of ' + this.maxfiles + ' or less.',
                  type    : 'error',
                  id      : 'asset_notifications'
                });
                break;
            case 'FileTooLarge':
                $.cpbar({
                  message : 'The file "' + file.name + '" is larger than the allowed limit of ' + this.maxfilesize + 'MB.',
                  type    : 'error',
                  id      : 'asset_notifications'
                });
                break;
            default:
                break;
        }
    },
    maxfiles: 25,
    maxfilesize: 20,    // max file size in MBs
    dragOver: function(e) {
      // User dragging files over #dropzone
      if (e.currentTarget == e.target) {
        // Dragging over #page, remove highlights
        hover_folder.removeClass('hovered');
        return true;
      }

      // Need to work out if we need to highlight a folder
      var tmp_hover_folder = ($(e.target).is('li.folder, li.up')) ? $(e.target) : $(e.target).parents('li.folder, li.up');
      if (tmp_hover_folder == hover_folder) {
        // We have the same target, no need to do anything further
        return true;
      } else {
        // We have a new target, clear the old one
        hover_folder.removeClass('hovered');
        hover_folder = tmp_hover_folder;
        if (hover_folder.length) {
          // New target is a folder
          hover_folder.addClass('hovered');
        }
      }
    },
    dragLeave: function() {
      // User dragging files out of #dropzone
      hover_folder.removeClass('hovered');
    },
    drop: function(e) {
      // Original filedrop selector targeted
      if (e.currentTarget == e.target) return true;

      // File dropped on something else, work out if it's a folder
      var folder;
      folder = ($(e.target).is('li.folder, li.up')) ? $(e.target) : $(e.target).parents('li.folder, li.up');
      if (folder.length) {
        // We want to upload to a specific directory.
        this.data["upload[directory]"] = getDirFromUrl(folder.find('span.link a').attr('href'));
      }
    },
    uploadStarted: function(i, file, len){
      upload_files.push(file);

      var message = len > 1 ? ' files...' : ' file...';
      $.cpbar({
        message  : 'Uploading ' + len + message,
        type     : 'info',
        id       : 'asset_notifications',
        duration : 45000
      });
    },
    uploadFinished: function(i, file, response, time) {
      // Remove the file from the queued files array
      var pos = jQuery.inArray(file, upload_files);
      if (pos !== -1) {
        upload_files.splice(pos, 1);
      }

      // Check whether we have any other files waiting to finish
      if (upload_files.length == 0) {
          location.reload();
      }
    },
    progressUpdated: function(i, file, progress) {
      $.cpbar({
        message : 'Uploading file "' + file.name + '" ' + progress + '%',
        type    : 'info',
        id      : 'asset_notifications'
      });
    }
});
</script>