[?php if ($field->isPartial()): ?]
  [?php include_partial('<?php echo $this->getModuleName() ?>/'.$name, array('type' => 'filter', 'form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?]
[?php elseif ($field->isComponent()): ?]
  [?php include_component('<?php echo $this->getModuleName() ?>', $name, array('type' => 'filter', 'form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?]
[?php else: ?]
    <div class="form-row">
      <div class="label">
        [?php echo $form[$name]->renderLabel($label) ?]
        [?php if ($help || $help = $form[$name]->renderHelp()): ?]
          <div class="help">[?php echo __($help, array(), '<?php echo $this->getI18nCatalogue() ?>') ?]</div>
        [?php endif; ?]
      </div>
        [?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?]
        [?php echo $form[$name]->renderError() ?]
    </div>
[?php endif; ?]
