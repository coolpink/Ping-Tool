<?php use_helper('I18N', 'Date') ?>
<?php include_partial('cpCms/assets') ?>
<script type="text/javascript">
  var allowedChildren = {};
  $(function (){
    $('#main-center').layout({ applyDefaultStyles: false, spacing_open: 0 });
    $('.listing').cpSelectable({
      elements : 'tbody tr',
      multiselect : false,
      dragover : $('#main-center .ui-layout-center'),
      mouseenter: function (elm){
        $(elm).toggleClass("hovered", true);
      },
      mouseleave : function (elm){
        $(elm).toggleClass("hovered", false);
      },
      change: function (selected, deselected){
        selected.toggleClass("selected", true);
        deselected.toggleClass("selected", false);
      }
    });
    var menus = {
      'add_new' : {
            'label'		  : 'Add New',
            'icon' 		  : '/cpCmsPlugin/images/add.png',
            'position' 	: 'left',
            'callback'	: function (e){},
            'children' : []
      },
      'edit' : {
            'label'		  : 'Edit',
            'icon' 		  : '/cpCmsPlugin/images/color_line.png',
            'position' 	: 'left',
            'callback'	: function (e){},
            'children' : []
      },
      'delete_item' : {
            'label'		  : 'Delete',
            'icon' 		  : '/cpCmsPlugin/images/edittrash.png',
            'position' 	: 'left',
            'callback'	: function (e){},
            'children' : []
      }
    };


    $('#tree tr').each(function (){
      var add_new = $.extend({}, menus.add_new);
      var edit = $.extend({}, menus.edit);
      edit.href = $(this).find("li.sf_admin_action_edit a").attr("href");
      var delete_item = $.extend({}, menus.delete_item);
      delete_item.href = $(this).find("li.sf_admin_action_delete a").attr("href");
      delete_item.callback = $(this).find("li.sf_admin_action_delete a").attr("onclick");
      var rel = $(this).attr("rel");
      var list = [];
      if (allowedChildren[rel] != null){
        add_new.children = allowedChildren[rel];
        list.push(add_new);
      }
	  if (canUserEdit[rel] != null)
	  {
		list.push(edit);
      }
      //add_new, edit, delete_item
      if (canUserDelete[rel] != null)
      {
      	list.push(delete_item);
      }

      $(this).contextmenu({},list);
    });

  });

</script>

<form action="<?php echo url_for('path_collection', array('action' => 'batch')) ?>" method="post">


  <div class="ui-layout-center" id="main-center">

    <div class="ui-layout-north">

    <?php include_partial('cpCms/flashes') ?>

    <?php //include_partial('cpCms/list_actions', array('helper' => $helper)) ?>

      <h1><?php echo __('Content Management', array(), 'messages') ?></h1>

    <?php include_partial('cpCms/list_header', array('pager' => $pager)) ?>

    </div>

    <div class="ui-layout-center" style="overflow: auto;">

    <?php include_partial('cpCms/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper, 'user'=>$sf_user)) ?>

    </div>



  </div>


</form>
<style type="text/css">
	th#sf_admin_list_th_actions {
		width: 250px;
	}
	.sf_admin_list_th_meta_visible_in_navigation {
		width: 200px;
	}
</style>

