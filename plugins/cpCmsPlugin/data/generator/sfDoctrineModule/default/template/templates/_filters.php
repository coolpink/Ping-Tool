[?php use_stylesheets_for_form($form) ?]
[?php use_javascripts_for_form($form) ?]


<form action="[?php echo url_for('<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'filter')) ?]" id="filter-form" method="post">
  <div class="wizard-content sf_admin_filter">

    [?php if ($form->hasGlobalErrors()): ?]
      [?php echo $form->renderGlobalErrors() ?]
    [?php endif; ?]
    <table cellspacing="0">
      <tbody>
        [?php foreach ($configuration->getFormFilterFields($form) as $name => $field): ?]
        [?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?]
          [?php include_partial('<?php echo $this->getModuleName() ?>/filters_field', array(
            'name'       => $name,
            'attributes' => $field->getConfig('attributes', array()),
            'label'      => $field->getConfig('label'),
            'help'       => $field->getConfig('help'),
            'form'       => $form,
            'field'      => $field,
            'class'      => 'sf_admin_form_row sf_admin_'.strtolower($field->getType()).' sf_admin_filter_field_'.$name,
          )) ?]
        [?php endforeach; ?]
      </tbody>
    </table>
    [?php echo $form->renderHiddenFields() ?]

  </div>
  <ul class="wizard-buttons">
    <li><a class="filter-wizard-cancel buttonanchor" href="javascript:void(0)">Cancel</a></li>
    <li>[?php echo link_to(__('Reset', array(), 'sf_admin'), '<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'filter'), array('query_string' => '_reset', 'method' => 'post', "class"=>"buttonanchor")) ?]</li>
    <li><input type="submit" value="[?php echo __('Filter', array(), 'sf_admin') ?]" class="green" /></li>
  </ul>
</form>

