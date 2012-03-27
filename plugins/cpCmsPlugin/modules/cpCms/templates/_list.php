<script type="text/javascript">
    var moveUrl = '<?php echo url_for("cpCms/move"); ?>';
    var insertBeforeUrl = '<?php echo url_for("cpCms/insertBefore"); ?>';
    var insertAfterUrl = '<?php echo url_for("cpCms/insertAfter"); ?>';
    var pageTypes = {};
    var childPageTypes = {};
    var canUserEdit = {};
    var canUserDelete = {};
</script>

<div>
  <?php if (!$pager->getNbResults()): ?>
    <p><?php echo __('No result', array(), 'sf_admin') ?></p>
  <?php else: ?>
    <table cellspacing="0" class="listing" id="tree">
      <thead>
        <tr>
          <?php include_partial('cpCms/list_th_tabular', array('sort' => $sort)) ?>
          <th id="sf_admin_list_th_actions"><?php echo __('Actions', array(), 'sf_admin') ?></th>
        </tr>
      </thead>
      <!-- tfoot>
        <tr>
          <th colspan="17">
            <?php if ($pager->haveToPaginate()): ?>
              <?php include_partial('cpCms/pagination', array('pager' => $pager)) ?>
            <?php endif; ?>

            <?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults(), 'sf_admin') ?>
            <?php if ($pager->haveToPaginate()): ?>
              <?php echo __('(page %%page%%/%%nb_pages%%)', array('%%page%%' => $pager->getPage(), '%%nb_pages%%' => $pager->getLastPage()), 'sf_admin') ?>
            <?php endif; ?>
          </th>
        </tr>
      </tfoot -->
      <tbody>
        <?php
        $cached_ids = array();
        foreach ($pager->getResults() as $p)
        {
          $cached_ids[] = $p;
        }
        include_partial("cpCms/cached_list", array("pager"=>$pager, "cached_ids"=>$cached_ids, "helper"=>$helper));
        ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<style type="text/css">

  th.sf_admin_list_th_created_at,
  th.sf_admin_list_th_updated_at,
  td.sf_admin_list_td_created_at,
  td.sf_admin_list_td_updated_at {
    display: none;
      width: 0;
  }
  th.sf_admin_list_th_slug,
  td.sf_admin_list_td_slug,
  th.sf_admin_list_th_formatted_template_type,
  td.sf_admin_list_td_formatted_template_type {
    white-space: nowrap;
  }
  th.sf_admin_list_th_formatted_template_type {
	  width: 130px;
  }
  th.sf_admin_list_th_slug {
	  width: 27%;
  }
  th.sf_admin_list_th_visible_in_navigation {
    width: 130px;
  }

</style>
<script type="text/javascript">
     $('table#tree td.sf_admin_list_td_meta_navigation_title').wrapInner("<div><span class='file-name' /></div>");
     $("table#tree").treeTable({
            treeColumn: 0
    });
    $('table#tree span.expander').mouseenter(function (){
      $(this).toggleClass("hovered", true);
    }).mouseleave(function (){
      $(this).toggleClass("hovered", false);
    }).mousedown(function (e){
      e.stopPropagation();
      return false;
    }).dblclick(function(e){
      e.stopPropagation();
      return false;
    })
    $("table#tree tbody tr").eq(0).expand();
</script>

