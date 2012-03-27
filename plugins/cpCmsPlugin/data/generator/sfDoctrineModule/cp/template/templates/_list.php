<div>
  [?php if (!$pager->getNbResults()): ?]
    <p>[?php echo __('No result', array(), 'sf_admin') ?]</p>
  [?php else: ?]
    <table cellspacing="0" class="listing">
      <thead>
        <tr>
<?php if ($this->configuration->getValue('list.batch_actions')): ?>
          <th id="sf_admin_list_batch_actions" style="width:40px;"><span><input id="sf_admin_list_batch_checkbox" type="checkbox" class="check_all" /></span></th>
<?php endif; ?>
          [?php include_partial('<?php echo $this->getModuleName() ?>/list_th_<?php echo $this->configuration->getValue('list.layout') ?>', array('sort' => $sort)) ?]
<?php if ($this->configuration->getValue('list.object_actions')): ?>
          <th id="sf_admin_list_th_actions"><span>[?php echo __('Actions', array(), 'sf_admin') ?]</span></th>
<?php endif; ?>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th colspan="<?php echo count($this->configuration->getValue('list.display')) + ($this->configuration->getValue('list.object_actions') ? 1 : 0) + ($this->configuration->getValue('list.batch_actions') ? 1 : 0) ?>">
          </th>
        </tr>
      </tfoot>
      <tbody>
        [?php foreach ($pager->getResults() as $i => $<?php echo $this->getSingularName() ?>): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?]
          <tr class="sf_admin_row [?php echo $odd ?]">
<?php if ($this->configuration->getValue('list.batch_actions')): ?>
            [?php include_partial('<?php echo $this->getModuleName() ?>/list_td_batch_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper)) ?]
<?php endif; ?>
            [?php include_partial('<?php echo $this->getModuleName() ?>/list_td_<?php echo $this->configuration->getValue('list.layout') ?>', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>)) ?]
<?php if ($this->configuration->getValue('list.object_actions')): ?>
            [?php include_partial('<?php echo $this->getModuleName() ?>/list_td_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper)) ?]
<?php endif; ?>
          </tr>
        [?php endforeach; ?]
      </tbody>
    </table>
  [?php endif; ?]
</div>
<script type="text/javascript">
/* <![CDATA[ */
$(function (){
  $('.check_all').click(function (){
    var self = $(this);
    if (self.attr("checked")){
      self.parents("table").find("tbody input[type=checkbox]:not(:checked)").trigger("mousedown").attr("checked", "checked");
    }else {
      self.parents("table").find("tbody input[type=checkbox]:checked").trigger("mousedown").removeAttr("checked");
    }
  });
  $('table.listing tr').dblclick(function (){
    location.href = $(this).find("a").eq(0).attr("href");
  });
});
/* ]]> */
</script>
