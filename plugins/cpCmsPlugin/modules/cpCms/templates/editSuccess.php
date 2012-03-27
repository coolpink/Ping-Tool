<?php use_helper('I18N', 'Date') ?>
<?php include_partial('cpCms/assets') ?>

<script type="text/javascript">
  $(function (){
    $('#main-center').layout({ applyDefaultStyles: false, spacing_open: 0 });
    collapseFieldsets();
  });
</script>

<?php
echo $form->renderFormTag(cpCmsGeneratorHelper::cms_url_for_form($form, "@path","?type=".$form->embededModelName.(empty($form->parent) ? "" : "&parent=".$form->parent)), array()); ?>

  <div class="ui-layout-center" id="main-center">

    <div class="ui-layout-north">

      <?php include_partial('cpCms/form_header', array('path' => $path, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>

      <h1><?php echo __('Edit '.$form->getObject()->getFormattedTemplateType() . ' "' . $form->getObject()->getMetaNavigationTitle() . '"', array(), 'messages') ?></h1>

      <?php include_partial('cpCms/flashes') ?>

    </div>

    <div class="ui-layout-center scrollable">

      <?php include_partial('cpCms/form', array('path' => $path, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>

    </div>

    <div class="ui-layout-south">

      <?php include_partial('cpCms/form_footer', array('path' => $path, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>

    </div>

  </div>

  <div class="sidebar scrollable metabar ui-layout-west">

    <?php include_partial('cpCms/metadatable', array('form' => $form, 'configuration' => $configuration)); ?>

  </div>

</form>