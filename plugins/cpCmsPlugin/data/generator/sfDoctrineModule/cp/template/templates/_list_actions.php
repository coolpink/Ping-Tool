<ul class="actions-bar">
  <?php if ($actions = $this->configuration->getValue('list.actions')): ?>
  <?php foreach ($actions as $name => $params): ?>
  <?php if ('_new' == $name): ?>
  <?php echo $this->addCredentialCondition('[?php echo $helper->linkToNew('.$this->asPhp($params).') ?]', $params)."\n" ?>
  <?php else: ?>
  <li class="sf_admin_action_<?php echo $params['class_suffix'] ?>">
  <?php echo $this->addCredentialCondition('[?php echo $helper->getLinkToAction(\''.$this->getModuleName().'\',\''.$name.'\','.$this->asPhp($params).') ?]', $params)."\n" ?>
  </li>
  <?php endif; ?>
  <?php endforeach; ?>
  <?php endif; ?>
  <?php if ($this->configuration->hasFilterForm()): ?>
    <li><a href="#" class="filter-button"><span>Filter List</span></a></li>
  <?php endif; ?>
</ul>
<script type="text/javascript">
$(function (){
  $('li a.filter-button')
    .attr("href", "javascript:void(0);")
    .click(function (){
      var filterwiz = $.wizard({
          id :      'filter',
          icon :    '/cpCmsPlugin/wizard/images/package_graphics.png',
          title :   'Filter List',
          elm :     '.filter-window'
      });
      filterwiz.find('.filter-wizard-cancel').click(function (e){
        e.preventDefault();
        e.stopPropagation();
        filterwiz.destroywizard();
        return false;
      });

    });
});
</script>