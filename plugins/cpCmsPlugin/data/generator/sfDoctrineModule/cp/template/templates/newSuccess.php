[?php use_helper('I18N', 'Date') ?]
[?php include_partial('<?php echo $this->getModuleName() ?>/assets') ?]

[?php echo form_tag_for($form, '@<?php echo $this->params['route_prefix'] ?>') ?]

  <div class="ui-layout-center" id="main-center">

    <div class="ui-layout-north">

      [?php include_partial('<?php echo $this->getModuleName() ?>/form_header', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?]

      <h1>[?php echo <?php echo $this->getI18NString('new.title') ?> ?]</h1>

      [?php include_partial('<?php echo $this->getModuleName() ?>/flashes') ?]

    </div>

    <div class="ui-layout-center scrollable">

      [?php include_partial('<?php echo $this->getModuleName() ?>/form', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?]

    </div>

    <div class="ui-layout-south">

      [?php include_partial('<?php echo $this->getModuleName() ?>/form_footer', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?]

    </div>

  </div>

  <div class="sidebar scrollable metabar ui-layout-west">

    [?php include_partial('<?php echo $this->getModuleName() ?>/metadatable', array('form' => $form, 'configuration' => $configuration)); ?]

  </div>

</form>
<script type="text/javascript">
  $(function (){
    $('#main-center').layout({ applyDefaultStyles: false, spacing_open: 0 });
    collapseFieldsets();
  });
</script>