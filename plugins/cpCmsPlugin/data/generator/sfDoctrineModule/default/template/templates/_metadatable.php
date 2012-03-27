<script type="text/javascript">

    [?php if ($title_map = $form->getTitleMap()):  ?]

      [?php
        $title_field = $form[$title_map];
        $meta_page_title_field = $form["meta_page_title"];
        $meta_navigation_title_field = $form["meta_navigation_title"];
        $meta_path_field = $form["meta_path"];
        $title_parent = $title_field->getParent();
        $page_title_parent = $meta_page_title_field->getParent();
        $title_fieldName = $title_field->getName();
        $meta_page_title_fieldName = $meta_page_title_field->getName();
        $meta_navigation_title_fieldName = $meta_navigation_title_field->getName();
        $meta_path_fieldName = $meta_path_field->getName();
        if ($title_parent != null){
         $title_fieldName = $title_parent->getWidget()->generateName($title_fieldName);
         $meta_page_title_fieldName = $page_title_parent->getWidget()->generateName($meta_page_title_fieldName);
         $meta_navigation_title_fieldName = $page_title_parent->getWidget()->generateName($meta_navigation_title_fieldName);
         $meta_path_fieldName = $page_title_parent->getWidget()->generateName($meta_path_fieldName);
        }

      ?]
      $(function (){
        var titlefield = $("input[name='[?php echo $title_fieldName; ?]'],"+
                              "textarea[name='[?php echo $title_fieldName; ?]']"+
                              "select[name='[?php echo $title_fieldName; ?]']")
        $("input[name='[?php echo $meta_page_title_fieldName; ?]'],"+
          "input[name='[?php echo $meta_navigation_title_fieldName; ?]'],"+
          "input[name='[?php echo $meta_path_fieldName; ?]']").each(function (){
            if ($(this).val() != titlefield.val() || $(this).val() != titlefield.slugIt({ output: 'return' })){
              $(this).data("changed", "yes");
            }
          }).keydown(function(){
            $(this).data("changed", "yes");
          });

        titlefield.keyup(doOnChange).change(doOnChange);
      });
      function doOnChange(e){
          var self = $(this);
          $("input[name='[?php echo $meta_page_title_fieldName; ?]'],"+
            "input[name='[?php echo $meta_navigation_title_fieldName; ?]']").each(function (){
            if ($(this).val() == ""){
              $(this).data("changed", "no");
            }
            if ($(this).data("changed") != "yes"){
              $(this).val(self.val());
            }
          });
          $("input[name='[?php echo $meta_path_fieldName; ?]']").each(function (){
            if ($(this).val() == ""){
              $(this).data("changed", "no");
            }
            if ($(this).data("changed") != "yes"){
              $(this).val(self.slugIt({ output: 'return' }));
            }
          });
      }

    [?php endif; ?]

  </script>

    [?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?]

     [?php foreach($fields as $name => $field): ?]

      [?php if (substr($name, 0, 5) == "meta_"): ?]

        [?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?]
        [?php include_partial('<?php echo $this->getModuleName() ?>/form_field', array(
          'name'       => $name,
          'attributes' => $field->getConfig('attributes', array()),
          'label'      => $field->getConfig('label'),
          'help'       => $field->getConfig('help'),
          'form'       => $form,
          'field'      => $field,
          'class'      => 'form-row sf_admin_'.strtolower($field->getType()).' sf_admin_form_field_'.$name,
        )) ?]

      [?php endif; ?]

     [?php endforeach; ?]

    [?php endforeach; ?]