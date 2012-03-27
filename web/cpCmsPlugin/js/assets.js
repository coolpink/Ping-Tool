/**
 * General asset manager events
 */
$(function () {

  /**
   * Select button action
   */
  $('#cp_media_browser_select').click(function (e) {
    e.preventDefault();
    sfmb_select($('#cp_media_browser_list').cpSelectable('getSelected')); // Select selected item (assets_functions.js)
  });

  /**
   * Rename button action
   */
  $('#cp_media_browser_rename').click(function (e) {
    e.preventDefault();
    sfmb_rename($('#cp_media_browser_list').cpSelectable('getSelected')); // Rename selected item (assets_functions.js)
  });

  /**
   * Delete button action
   */
  $('#cp_media_browser_delete').click(function (e) {
    e.preventDefault();
    sfmb_delete($('#cp_media_browser_list').cpSelectable('getSelected')); // Delete selected item/s (assets_functions.js)
  });

  /**
   * Delete key press
   */
  $(document).keydown(function(e) {
    if (e.which == 46) {
      // Delete key pressed and we have selected items
      sfmb_delete($('#cp_media_browser_list').cpSelectable('getSelected')); // Delete selected item/s (assets_functions.js)
    }
  });

  /**
   * Sidebar folder navigation
   */
  createTree('#cp_media_browser_structure');

  /**
   * Close form buttons
   */
  $('#cp_media_browser_forms a.closeForm').click(function(e) {
    e.preventDefault();
    $(this).closest('div').hide();
  });

  /**
   * Create new directory form button
   */
  $('#cp_media_browser_create_dir').click(function(e) {
    e.preventDefault();
    $('#cp_media_browser_upload').hide();
    $('#cp_media_browser_mkdir').show();
    $('#directory_name').focus();
  });

  /**
   * Upload new asset form button
   */
  $('#cp_media_browser_upload_file').click(function(e) {
    e.preventDefault();
    $('#cp_media_browser_mkdir').hide();
    $('#cp_media_browser_upload').show();
  });

  /**
   * Drag to move
   */
  $('#cp_media_browser_list li:not(.up)').draggable({
    revert: 'invalid',
    revertDuration: 10,
    opacity: 0.7,
    distance: 20,
    helper: function(){
      var selected = $('#cp_media_browser_list .selected');
      var cp_selected = $('#cp_media_browser_list').cpSelectable('getSelected');
      if (jQuery.inArray(this, cp_selected) == -1) {
        // We must be receiving an element that hasn't been 'selected' yet ie. it got dragged straight away
        selected = $(this);
        cp_selected.toggleClass("selected", false);
        cp_selected = $([this]);
        cp_selected.toggleClass("selected", true);
        smb_update_file_info(cp_selected);
        smb_update_action_buttons(cp_selected);
      }
      var container = $('<div/>').attr('id', 'draggingContainer');
      container.append(selected.clone());
      return container;
    },
    containment: '#page',
    zIndex: 100,
    start: function(event, ui) {
        $(document.body).unbind("click.doc");
    },
    stop : function(e,ui){
      setTimeout(function(){$(document.body).bind("click.doc", smb_deselected);}, 300);
    }
  });
  $('#cp_media_browser_list .folder, #cp_media_browser_list .up').droppable({
    hoverClass: 'hovered',
    tolerance: 'pointer',
    drop: function(event, ui) {
      var target = $(this);
      var target_value = getDirFromUrl(target.find('.action .link a').attr('href'));
      var files = [];
      var cp_selected = $('#cp_media_browser_list').cpSelectable('getSelected');
      cp_selected.each(function (i, el) {
        var source_value = $(el).find('.action .link a').attr('href');
        if(!$(el).hasClass('file'))
        {
          source_value = getDirFromUrl(source_value);
        }
        files.push({file: source_value, dir: target_value});
      });
      var data = { 'data' : files };
      var callback = function(r, s){
        $.cpbar({
          message : r.message,
          type    : r.status,
          id      : 'asset_notifications'
        });
        if(r.status == 'success' || r.status == 'notice')
        {
          cp_selected.each(function (i, el){
            $(el).remove();
          });
          cp_selected = $([]);
        }
        smb_update_file_info(cp_selected);
        smb_update_action_buttons(cp_selected);
      };
      $.post(move_file_url, data, callback, 'json');
    }
  });

  /**
   * Double click to open
   */
  $('#cp_media_browser_list li').dblclick(function(e) {
    // Double click - open the item
    if ($(this).hasClass('file')) {
      window.open($(this).find('a').eq(0).attr('href'));
    } else {
      // Directory
      window.location = $(this).find('a').eq(0).attr('href');
    }
  });

  /**
   * Context menu
   * disabled by mikee 05/08/2011. The rest of the delete/rename is relying on post data.
   * Not sure if blair just didnt have time to finish this or if it's something he missed.
   */
  //$('#cp_media_browser_list li.selectable-cp').contextmenu();
});