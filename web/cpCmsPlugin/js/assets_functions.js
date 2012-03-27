/**
 * General asset functions
 */

/**
 * Call when items are deselected
 */
function smb_deselected() {
  var cp_selected = $('#cp_media_browser_list').cpSelectable('getSelected');
  smb_update_file_info(cp_selected);
  smb_update_action_buttons(cp_selected);
//  $('.jeegoocontext').hide();
};

/**
 * Updates the #file_info div with details of the selected items
 * @param cp_selected array of selected items
 */
function smb_update_file_info(cp_selected) {
  if (cp_selected.length > 1) {
    $('#cp_media_browser_file_info div.name').html(cp_selected.length + ' items selected');
    $('#cp_media_browser_file_info div.type').hide();
    $('#cp_media_browser_file_info div.size').hide();
    $('#cp_media_browser_file_info div.dimensions').hide();
    $('#cp_media_browser_file_info').show();
  }
  else if (cp_selected.length == 1) {
    $('#cp_media_browser_file_info div.name').html(fitStringToWidth($(cp_selected[0]).find('span.fullname').html(), 180));
    $('#cp_media_browser_file_info div.type').show();
    $('#cp_media_browser_file_info div.type span').html($(cp_selected[0]).find('span.type').html());
    if ($(cp_selected[0]).find('div.info').length) {
      $('#cp_media_browser_file_info div.size').show();
      $('#cp_media_browser_file_info div.size span').html($(cp_selected[0]).find('span.size').html());
      if ($(cp_selected[0]).find('span.dimensions').length) {
        $('#cp_media_browser_file_info div.dimensions').show();
        $('#cp_media_browser_file_info div.dimensions span').html($(cp_selected[0]).find('span.dimensions').html());
      } else {
        $('#cp_media_browser_file_info div.dimensions').hide();
      }
    } else {
      $('#cp_media_browser_file_info div.size').hide();
      $('#cp_media_browser_file_info div.dimensions').hide();
    }
    $('#cp_media_browser_file_info').show();
  } else {
    $('#cp_media_browser_file_info').hide();
  }
};

/**
 * Shows/hides action buttons
 * @param cp_selected array of selected items
 */
function smb_update_action_buttons(cp_selected) {
  if (cp_selected.length) {
    $('#cp_media_browser_actions li.spacer').show();
    $('#cp_media_browser_actions li.sf_admin_action_delete').show();
    $('#cp_media_browser_actions li.sf_admin_action_rename').hide();
    if (cp_selected.length == 1) {
      $('#cp_media_browser_actions li.sf_admin_action_select').toggle($('body').hasClass('widget') && !$('#modal', parent.document).length);
      $('#cp_media_browser_actions li.sf_admin_action_rename').show();
    }
  } else {
    $('#cp_media_browser_actions li.spacer').hide();
    $('#cp_media_browser_actions li.sf_admin_action_select').hide();
    $('#cp_media_browser_actions li.sf_admin_action_delete').hide();
    $('#cp_media_browser_actions li.sf_admin_action_rename').hide();
  }
};

/**
 * Fits a string to a given width
 * @param str A string where html-entities are allowed but no tags.
 * @param width The maximum allowed width in pixels
 * @param className A CSS class name with the desired font-name and font-size. (optional)
 */
function fitStringToWidth(str, width, className) {
  /**
   * A helper to escape 'less than' and 'greater than'
   * @param s the string
   */
  function _escTag(s){ return s.replace("<","&lt;").replace(">","&gt;");}

  // Create a span element that will be used to get the width
  var span = document.createElement("span");
  // Allow a classname to be set to get the right font-size.
  if (className) span.className=className;
  span.style.display='inline';
  span.style.visibility = 'hidden';
  span.style.padding = '0px';
  document.body.appendChild(span);

  var result = _escTag(str); // default to the whole string
  span.innerHTML = result;
  // Check if the string will fit in the allowed width. NOTE: if the width
  // can't be determined (offsetWidth==0) the whole string will be returned.
  if (span.offsetWidth > width) {
    var posStart = 0, posMid, posEnd = str.length, posLength;
    // Calculate (posEnd - posStart) integer division by 2 and
    // assign it to posLength. Repeat until posLength is zero.
    while (posLength = (posEnd - posStart) >> 1) {
      posMid = posStart + posLength;
      //Get the string from the begining up to posMid;
      span.innerHTML = _escTag(str.substring(0,posMid)) + '&hellip;';

      // Check if the current width is too wide (set new end)
      // or too narrow (set new start)
      if ( span.offsetWidth > width ) posEnd = posMid; else posStart=posMid;
    }

    result = '<abbr title="' + str.replace(/\"/g, "&quot;") + '">' +
      _escTag(str.substring(0, Math.floor(posStart / 2))) +
      '&hellip;' +
      _escTag(str.substring(str.length - Math.ceil(posStart / 2), str.length)) + '<\/abbr>';
  }
  document.body.removeChild(span);
  return result;
};

/**
 *  Delete a file
 */
function sfmb_delete(cp_selected){
  if (cp_selected.length < 1) return false; // Make sure we have at least one selected
  var delete_msg, files_count = 0, dirs_count = 0, data = [];

  // check number of files and number of directories
  cp_selected.each(function (i, el) {
    if($(el).hasClass('file')) {
      files_count++;
    } else {
      dirs_count++;
    }
    var file, link = $(el).find('.action .link a');
    if(!$(el).hasClass('file')) file = getDirFromUrl(link.attr('href'));
    else file = link.attr('href');
    data.push(file);
  });

  // Work out the message to show
  switch (files_count) {
    case 0: delete_msg = (dirs_count == 1) ?
                delete_msg_dir.replace('%s%', $(cp_selected[0]).find('span.fullname').text()) :
                delete_msg_many_dir.replace('%d%', dirs_count);
            break;
    case 1: if (dirs_count < 1) delete_msg = delete_msg_file.replace('%s%', $(cp_selected[0]).find('span.fullname').text());
            else if (dirs_count == 1) delete_msg = delete_msg_many_file_dir.replace('%f%', files_count + dirs_count).replace('%d%', dirs_count);
            else delete_msg = delete_msg_many_file_dirs.replace('%f%', files_count + dirs_count).replace('%d%', dirs_count);
            break;
    default: // More than 1
            if (dirs_count < 1) delete_msg = delete_msg_many_file.replace('%d%', files_count);
            else if (dirs_count == 1) delete_msg = delete_msg_many_file_dir.replace('%f%', files_count + dirs_count).replace('%d%', dirs_count);
            else delete_msg = delete_msg_many_file_dirs.replace('%f%', files_count + dirs_count).replace('%d%', dirs_count);
  }

  if (window.confirm(delete_msg)) {
    var data = {files: data};
    var callback = function(r,s){
      $.cpbar({
        message : r.message,
        type    : r.status,
        id      : 'asset_notifications'
      });
      if (r.status == 'notice' || r.status == 'success') {
        cp_selected.each(function(i, el) {
         $(el).remove();
        });
        cp_selected = $([]);
      }
      smb_update_file_info(cp_selected);
      smb_update_action_buttons(cp_selected);
      updateTreeStructure('#cp_media_browser_structure');
    };
    $.post(delete_url, data, callback, 'json');
  }
};

/**
 * Updates the jsTree for the given selector
 * @param selector jQuery
 */
function updateTreeStructure(selector) {
  $.getJSON(tree_url + '?dir=' + dir_url, function(data) {
    if (data.html) {
      /*
       * Remove the existing tree, update the content and build the new tree.
       * Need to do it this way, otherwise the tree doesn't open to the correct folder.
       */
      $(selector).jstree('destroy');
      $(selector).html(data.html);
      createTree(selector);
    }
  });
};

/**
 *  Select a file for TinyMCE
 */
function sfmb_select(cp_selected){
  if (cp_selected.length != 1) return false; // Make sure we only have one selected

  var l = $(cp_selected[0]);
  var link = l.find('.action .link a');
  var file;
  if(!l.hasClass('file')) {file = getDirFromUrl(link.attr('href'));}
  else {file = link.attr('href');}
  // Do something with file
  var window_manager = parent.window.opener.window_manager;
  window_manager.callback(file);
};

/**
 *  Rename a file
 */
function sfmb_rename(cp_selected){
  if (cp_selected.length != 1) return false; // Make sure we only have one selected

  var l = $(cp_selected[0]);
  // Ask for the new filename, giving the old one as the default
  var new_name = jQuery.trim(window.prompt(rename_msg, l.find('span.fullname').text()));
  if(new_name && l.find('span.fullname').text() != new_name) {
    var link = l.find('.action .link a');
    var file;
    if(!l.hasClass('file')) {file = getDirFromUrl(link.attr('href'));}
    else {file = link.attr('href');}
    var data = {file: file, name: new_name};
    var callback = function(r,s){
      $.cpbar({
        message : r.message,
        type    : r.status,
        id      : 'asset_notifications'
      });
      if(typeof(r['name']) != 'undefined') {
        l.find('label').html(fitStringToWidth(r['name']), 180);
        l.find('span.fullname').html(r['name']);
        if(!l.hasClass('file')) document.location.href = document.location.href;
        else file = link.attr('href', r['url']);
      }
      smb_update_file_info(cp_selected);
      smb_update_action_buttons(cp_selected);
      updateTreeStructure('#cp_media_browser_structure');
    };
    $.post(rename_file_url, data, callback, 'json');
  }
};

/**
 * Reads the ?dir= url value
 * @param string url
 * @return string the dir value
 */
function getDirFromUrl(url) {
  var regex = new RegExp("[\\?&]dir=([^&#]*)");
  results = regex.exec(url);
  return results[1] ? decodeURIComponent(results[1]).replace(/\+/g, ' ') : '/';
};

/**
 * Creates a tree structure
 * @param selector jQuery
 * @requires jquery.jstree.js
 */
function createTree(selector) {
  $(selector).jstree({
      'themes' : {
        'theme' : 'apple',
        'url' : '/cpCmsPlugin/js/themes/cp/style.css'
      },
      'plugins' : [ 'themes', 'html_data', 'cookies' ]
    });
}