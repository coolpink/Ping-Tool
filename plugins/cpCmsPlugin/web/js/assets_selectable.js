$(function (){
  $('#cp_media_browser_list').cpSelectable({
    elements : 'li.selectable-cp',
    dragover : $('#page'),
    mouseenter: function (elm){
      $(elm).toggleClass("hovered", true);
    },
    mouseleave : function (elm){
      $(elm).toggleClass("hovered", false);
    },
    change: function (selected, deselected){
      selected.toggleClass("selected", true);
      deselected.toggleClass("selected", false);
      smb_update_file_info(selected);
      smb_update_action_buttons(selected);
    }
  });
});