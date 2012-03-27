[?php use_helper('I18N', 'Date') ?]
[?php include_partial('<?php echo $this->getModuleName() ?>/assets') ?]
<script type="text/javascript">
  $(function (){
    $('#main-center').layout({ applyDefaultStyles: false, spacing_open: 0 });
    $('.listing').cpSelectable({
      elements : 'tbody tr',
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
  });
</script>
<?php if ($this->configuration->getValue('list.batch_actions')): ?>

  <form action="[?php echo url_for('<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'batch')) ?]" method="post">

<?php endif; ?>

<div class="ui-layout-center" id="main-center">

  <div class="ui-layout-north">

    [?php include_partial('<?php echo $this->getModuleName() ?>/flashes') ?]

    [?php include_partial('<?php echo $this->getModuleName() ?>/list_actions', array('helper' => $helper)) ?]

    [?php include_partial('<?php echo $this->getModuleName() ?>/list_title', array('helper' => $helper, 'params' => isset($params) ? $params : array())) ?]

    [?php include_partial('<?php echo $this->getModuleName() ?>/list_header', array('pager' => $pager)) ?]

  </div>

  <div class="ui-layout-center" style="overflow: auto;">

    [?php include_partial('<?php echo $this->getModuleName() ?>/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?]

  </div>

  <div class="ui-layout-south">

    [?php include_partial('<?php echo $this->getModuleName() ?>/list_footer', array('pager' => $pager, 'helper' => $helper)) ?]

  </div>

</div>

<?php if ($this->configuration->getValue('list.batch_actions')): ?>

  </form>

<?php endif; ?>

<?php if ($this->configuration->hasFilterForm()): ?>

  <div class="filter-window" style="display: none;">

    [?php include_partial('<?php echo $this->getModuleName() ?>/filters', array('form' => $filters, 'configuration' => $configuration)) ?]

  </div>

<?php endif; ?>