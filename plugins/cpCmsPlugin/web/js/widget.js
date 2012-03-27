/**
 * This file links the form widget sfWidgetFormInputImage fields to the asset manager
 *
 * @package    cpMediaBrowser
 * @subpackage widget
 * @author     Coolpink <dev@coolpink.net>
 */

if(!jQuery || !jQuery.ui)
{
  alert('Eek! We need jQuery and jQuery UI! Please contact your web team and tell them to include these on this page.');
}

$(function() {
  /*
   * Image functions
   */

  /* Select/replace image */
  $('a.mbp_form_select, a.mbp_form_replace').click(function(e) {
    e.preventDefault();
    var link = $(this);
    var modal = $.wizard({
      id :      'modal',
      name :    'cpMediaBrowser',
      icon :    '/cpAdminGeneratorPlugin/wizard/images/package_graphics.png',
      title :   $(this).text(),
      url:      this.href,
      buttons:  [{
                  text: 'Cancel',
                  style: 'grey',
                  callback  : function (e){
                    e.preventDefault();
                    modal.destroywizard();
                  }
                }, {
                  text: 'Use image',
                  style: 'green',
                  callback  : function (e){
                    e.preventDefault();
                    var selected = $('#modal iframe.frame').contents().find('#cp_media_browser_list li.selected');
                    if (selected.length == 1) {
                      selected = selected.find('div.action span.link a').attr('href');
                      link.siblings('input.sfWidgetFormInputImage,input.image').val(selected);
                     
                      var crop_path = selected;
                      var image = $('<img />').addClass('selected_image').attr('src', crop_path + '/max_width/450/max_height/450');
                      link.siblings('.selected_image').html(image);
                      link.text('Choose a different image');
                      link.siblings('a').show();
                      link.filter('a.mbp_form_select').hide();

                      modal.destroywizard();
                    } else {
                      $.cpbar({
                        message : 'Please select a single image and then click "Use image".',
                        type    : 'error',
                        id      : 'asset_notifications'
                      });
                      
                      return false;
                    }
                  }
                }]
    });
  });

  /* Crop image */
  $('a.mbp_form_crop').click(function(e) {
    e.preventDefault();
    launchCropper(this);
  });

  /* Remove image */
  $('a.mbp_form_delete').click(function(e) {
    e.preventDefault();
    $(this).siblings('input.sfWidgetFormInputImage').val('');
    $(this).siblings('.selected_image').html('No image selected.');
    $(this).siblings('a.mbp_form_replace').text('Choose an image');
    $(this).siblings('a').hide();
    $(this).hide();
    $(this).siblings('a.mbp_form_replace').show();
  });

  /*
   * File functions
   */

  /* Select/replace file */
  $('a.mbp_form_select_file, a.mbp_form_replace_file').click(function(e) {
    e.preventDefault();
    var link = $(this);
    var modal = $.wizard({
      id :      'modal',
      name :    'cpMediaBrowser',
      icon :    '/cpAdminGeneratorPlugin/wizard/images/package_graphics.png',
      title :   $(this).text(),
      url:      this.href,
      buttons:  [{
                  text: 'Cancel',
                  style: 'grey',
                  callback  : function (e){
                    e.preventDefault();
                    modal.destroywizard();
                  }
                }, {
                  text: 'Use file',
                  style: 'green',
                  callback  : function (e){
                    e.preventDefault();
                    var selected = $('#modal iframe.frame').contents().find('#cp_media_browser_list li.selected');
                    if (selected.length == 1) {
                      selected = selected.find('div.action span.link a').attr('href');
                      link.siblings('input.sfWidgetFormInputFileAsset').val(selected);

                      var crop_path = selected;
                      link.siblings('.selected_file').html(selected);
                      link.text('Choose a different file');
                      link.siblings('a').show();
                      link.filter('a.mbp_form_select').hide();

                      modal.destroywizard();
                    } else {
                      $.cpbar({
                        message : 'Please select a single file and then click "Use file".',
                        type    : 'error',
                        id      : 'asset_notifications'
                      });

                      return false;
                    }
                  }
                }]
    });
  });

  /* Remove file */
  $('a.mbp_form_delete_file').click(function(e) {
    e.preventDefault();
    $(this).siblings('input.sfWidgetFormInputFileAsset').val('');
    $(this).siblings('.selected_file').html('No file selected.');
    $(this).siblings('a.mbp_form_replace_file').text('Choose a file');
    $(this).siblings('a').hide();
    $(this).hide();
    $(this).siblings('a.mbp_form_replace_file').show();
  });
});
