/**
 * This file displays previews of youtube videos shwon with sfWidgetFormYoutube
 *
 * @package    sfGdataPlugin
 * @subpackage widget
 * @author     Coolpink <dev@coolpink.net>
 */

if(!jQuery || !jQuery.ui)
{
  alert('Eek! We need jQuery and jQuery UI! Please contact your web team and tell them to include these on this page.');
}

$(function() {
  var url = 'http://img.youtube.com/vi/%YOUTUBE_ID%/1.jpg';
  $('select.sfWidgetFormYoutube').change(function(e) {
    $(this).siblings('img.sfWidgetFormYoutube').remove();
    var youtube_id = $(this).find('option:selected').val();
    if (youtube_id) {
      $(this).before($('<img />').attr('class', 'sfWidgetFormYoutube').attr('src', url.replace('%YOUTUBE_ID%', youtube_id)));
    }
  });
});