<div class="table-footer">

    <ul class="sf_admin_actions">
		[?php include_partial('<?php echo $this->getModuleName() ?>/list_batch_actions', array('helper' => $helper)) ?]
    </ul>
	
	[?php if ($pager->haveToPaginate()): ?]
	  [?php include_partial('<?php echo $this->getModuleName() ?>/pagination', array('pager' => $pager)) ?]
	[?php endif; ?]

</div>