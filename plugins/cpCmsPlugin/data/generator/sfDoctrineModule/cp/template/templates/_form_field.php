[?php
$widget = $form[$name]->getWidget();
$widgetClass = get_class($widget);
$use_attributes = $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes;
if ($widgetClass == "sfWidgetFormDoctrineChoice")
{
  if ($form[$name]->getWidget()->getOption("multiple") === true)
  {
      $widgetClass .= " list-multiple";
  }
}
$use_attributes["class"] = $widgetClass;

if ($widgetClass == "sfWidgetFormSchemaDecorator")
{
	$embedded = $form->getEmbeddedForm($name);

	if ($fieldsets = $embedded->getGroupedFields())
	{
		foreach ($fieldsets as $fieldset => $fields)
		{
		?]
			<fieldset class="form-fieldset" id="sf_fieldset_[?php echo preg_replace('/[^a-z0-9_]/', '_', strtolower($fieldset)) ?]">
		[?php if ('NONE' != $fieldset): ?]
			 <h2>[?php echo __($fieldset, array(), '<?php echo $this->getI18nCatalogue() ?>') ?]</h2>
 		 [?php endif; ?]
 		   <div class="fieldset-fields">
		[?php
			$newfields = array();
			foreach($fields as $subname ){
				if ($subname[0] == "_")
				{
					include_partial('<?php $mn = $this->getModuleName(); echo $mn == "cpCms" ? "global":$mn; ?>/'.substr($subname, 1), array('form' => $embedded, "parent_form" => $form));
				}
				else
				{
					$field = $embedded[$subname]->getWidget();

					if (!$field->isHidden()){
						include_partial('<?php echo $this->getModuleName() ?>/form_field', array(
						  'name'       => $subname,
						  'attributes' => $field->getAttributes(),
						  'label'      => $field->getLabel(),
						  'help'       => $widget->getHelp($subname),/*$field->get('help')*/
						  'form'       => $form[$name],
						  'field'      => null,
						  'class'      => 'form-row sf_admin_text sf_admin_form_field_'.$name,
						));
					 }
				  }
			}
		?]
		</div>
			</fieldset>

		[?php

		}
	}
	else
	{

		foreach ($widget->getFields() as $subname => $field){
		  if (!$field->isHidden()){
			include_partial('<?php echo $this->getModuleName() ?>/form_field', array(
			  'name'       => $subname,
			  'attributes' => $field->getAttributes(),
			  'label'      => $field->getLabel(),
			  'help'       => $widget->getHelp($subname),/*$field->get('help')*/
			  'form'       => $form[$name],
			  'field'      => null,
			  'class'      => 'form-row sf_admin_text sf_admin_form_field_'.$name,
			));
		  }
		}
	}
}else {
?]
	[?php if ($field != null && $field->isPartial()): ?]
	  [?php include_partial('<?php echo $this->getModuleName() ?>/'.$name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?]
	[?php elseif ($field != null && $field->isComponent()): ?]
	  [?php include_component('<?php echo $this->getModuleName() ?>', $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?]
	[?php else: ?]
	  <div class="[?php echo $class ?][?php $form[$name]->hasError() and print ' has-error' ?]">
		<div class="label">
		  [?php echo $form[$name]->renderLabel($label) ?]

		  [?php if ($help || $help = $form[$name]->renderHelp()): ?]
			<div class="help">
			  [?php echo __(strip_tags($help), array(), '<?php echo $this->getI18nCatalogue() ?>') ?]
			</div>
		  [?php endif; ?]
		</div>
		[?php echo $form[$name]->render($use_attributes); ?]
		[?php if ($form[$name]->hasError()): ?]
		  <div class="errors">
			[?php echo $form[$name]->renderError() ?]
		  </div>
		[?php endif; ?]
	  </div>
	[?php endif; ?]
[?php }
?]