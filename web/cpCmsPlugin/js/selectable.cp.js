Array.cpLast = function(arr) {
  return arr[arr.length - 1];
};

/**
 * Array of selected elements
 */
var cp_selected = $([]);

function smb_deselected() {
  cp_selected.toggleClass("selected", false);
  cp_selected = $([]);
  smb_update_file_info(cp_selected);
  smb_update_action_buttons(cp_selected);
  $('.jeegoocontext').hide();
}

$(function () {
  $('#cp_media_browser_structure a').mouseenter(function () {
    $(this).toggleClass("highlight", true);
  }).mouseleave(function () {
    $(this).toggleClass("highlight", false);
  });

  $('#cp_media_browser_list .up').click(function () {
      window.location = $(this).find('a').eq(0).attr('href');
  });
  if (jQuery.fn.disableTextSelect) {
		$('#cp_media_browser_list .selectable-cp').disableTextSelect();
	}
  $('#cp_media_browser_list .up').bind('dragstart', function(e) { return false; }); // Disable dragging up folder
  $('#cp_media_browser_list .selectable-cp').mouseenter(function () {
    $(this).toggleClass("highlight", true);
  }).mouseleave(function () {
    $(this).toggleClass("highlight", false);
  }).mousedown(function(e) {
    // Need to prevent dragtoselect firing
    e.preventDefault();
    e.stopPropagation();
  }).click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    if (e.ctrlKey) {
      // Ctrl-click - add/remove individual element
      var loc = jQuery.inArray(this, cp_selected);
      if (loc == -1) {
        // Element not in the array, add it
        cp_selected.push(this);
        cp_selected.toggleClass("selected", true);
      }
      else {
        // Element already exists, remove it
        cp_selected.eq(loc).toggleClass("selected", false);
        cp_selected.splice(loc, 1);
      }
    }
    else if (e.shiftKey) {
      // Shift-click - add all elements between cp_selected
      cp_selected.toggleClass("selected", false);
      var second_el_index = $(this).prevAll("li").length;
      var first_el = cp_selected.last();
      var first_el_index = $(cp_selected).prevAll("li").length;
      if (first_el_index == second_el_index) {
        // cp_selected the same element
        cp_selected.toggleClass("selected", false);
        cp_selected = $([this]);
        cp_selected.toggleClass("selected", true);
      }
      else if (first_el_index < second_el_index) {
        // cp_selected forwards
        cp_selected = first_el.nextUntil(":nth-child(" + (second_el_index + 1) + ")");
      }
      else if (first_el_index > second_el_index) {
        // cp_selected backwards
        cp_selected = first_el.prevUntil(":nth-child(" + (second_el_index + 1) + ")");
      }
      cp_selected.splice(0, 0, first_el[0]); // Push the first element onto the start of the array
      cp_selected.push(this); // Push the second element onto the end of the array
      cp_selected.toggleClass("selected", true);
    }
    else {
      // Regular click - select individual element
      cp_selected.toggleClass("selected", false);
      cp_selected = $([this]);
      cp_selected.toggleClass("selected", true);
    }
    smb_update_file_info(cp_selected);
    smb_update_action_buttons(cp_selected);
    $('ul.jeegoocontext').hide(); // Hide any left over context menus since they won't be cleaned up due to no bubbling 
  }).dblclick(function(e) {
    // Double click - open the item
    if ($(this).hasClass('file')) {
      window.open($(this).find('a').eq(0).attr('href'));
    } else {
      // Directory
      window.location = $(this).find('a').eq(0).attr('href');
    }
  }).mousedown(function(e) {
    if (e.which == 3) {
      // Right click
      if (jQuery.inArray(this, cp_selected) == -1) {
        // We must be receiving an element that hasn't been 'selected' yet ie. it got dragged straight away
        cp_selected.toggleClass("selected", false);
        cp_selected = $([this]);
        cp_selected.toggleClass("selected", true);
        smb_update_file_info(cp_selected);
        smb_update_action_buttons(cp_selected);
      }
      if (cp_selected.length == 1) {
        $('ul.jeegoocontext li.rename').show();
      } else {
        $('ul.jeegoocontext li.rename').hide();
      }
    }
  });

  $(document.body).bind("click.doc", smb_deselected);

  /**
   * Rename button
   */
  $('#cp_media_browser_rename').click(function (e) {
    e.preventDefault();
    sfmb_rename(cp_selected); // Rename selected item (list.jquery.js)
  });

  /**
   * Delete button
   */
  $('#cp_media_browser_delete').click(function (e) {
    e.preventDefault();
    sfmb_delete(cp_selected); // Delete selected item/s (list.jquery.js)
  });
});

/**
 * Delete key press
 */
$(document).keydown(function(e) {
  if (e.which == 46 && cp_selected.length > 0) {
    // Delete key pressed and we have selected items
    sfmb_delete(cp_selected); // Delete selected item/s (list.jquery.js)
  }
});

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
}

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
      $('#cp_media_browser_actions li.sf_admin_action_rename').show();
    }
  } else {
    $('#cp_media_browser_actions li.spacer').hide();
    $('#cp_media_browser_actions li.sf_admin_action_delete').hide();
      $('#cp_media_browser_actions li.sf_admin_action_rename').hide();
  }
}

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
  // can't be determinated (offsetWidth==0) the whole string will be returned.
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
}