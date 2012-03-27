<?php

/**
 * sfWidgetFormInputFileInputImageJCroppable represents an upload HTML input tag which will
 * also display the uploaded image with the JCrop functionality.
 *
 * @author     Rich Birch <rich@trafficdigital.com>
 */
class croppable extends sfWidgetForm
{
  private $hasImage = false;

  protected $idStub;

  /**
   * Constructor.
   *
   * Available options:
   *
   *  * file_src:     The current image web source path (required)
   *  * with_delete:  Whether to add a delete checkbox or not
   *  * delete_label: The delete label used by the template
   *  * image_field:
   *  * image_ratio:
   *  * invoker:
   *  * template:     The HTML template to use to render this widget
   *                  The available placeholders are:
   *                    * input (the image upload widget)
   *                    * delete (the delete checkbox)
   *                    * delete_label (the delete label text)
   *                    * file (the file tag)
   *  * form:
   *
   * In edit mode, this widget renders an additional widget named after the
   * file upload widget with a "_delete" suffix. So, when creating a form,
   * don't forget to add a validator for this additional field.
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormInputFile
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addRequiredOption('file_src');
    $this->addOption('with_delete', true);
    $this->addOption('delete_label', 'remove the current image');
    $this->addOption('image_field', null);
    $this->addOption('image_ratio', null);
    $this->addOption('invoker', null);
    $this->addOption('template', '%file%<br />%input%<br />%delete% %delete_label%');
    $this->addOption('form', null);
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $hidden =  new sfWidgetFormInputHidden(array(), array());
    $input = $hidden->render($name, "");
    $object = $this->getOption('invoker');



    $this->hasImage = (false !== $this->getOption('file_src')
      && substr($this->getOption('file_src'), -1) != '/');

    if (class_exists('sfJSLibManager'))
    {
      sfJSLibManager::addLib('jcrop');
    }

    if ($this->hasImage && $this->getOption('with_delete'))
    {
      $deleteName = ']' == substr($name, -1) ? substr($name, 0, -1).'_delete]' : $name.'_delete';
      $form = $this->getOption('form');

      //if ($form->getOption('embedded', false) && $form->getOption('parent_model', false))
      //{
      //  $delete = $form->getOption('parent_model')->getDeleteLinkFor($object);
      //  $deleteLabel = '';
//        $delete = '';
//        $deleteLabel = '';
      //}
      //else
      //{
        $delete = $this->renderTag('input', array_merge(array('type' => 'checkbox', 'name' => $deleteName), $attributes));
        $deleteLabel = $this->renderContentTag('label', $this->getOption('delete_label'), array_merge(array('for' => $this->generateId($deleteName))));
      //}
    }
    else
    {
      $delete = '';
      $deleteLabel = '';
    }

    $selectAssetLink = '<a href="javascript:void(0);" id="'.$this->getIdStub().'_select_link">Select Asset</a>';


    return strtr($this->getOption('template') . $this->getJCropJS(), array('%input%' => $input, '%delete%' => $delete, '%selectasset%' => $selectAssetLink, '%delete_label%' => $deleteLabel, '%file%' => $this->getFileAsTag($attributes)));
  }

  protected function getFileAsTag($attributes)
  {
    $id = $this->getIdStub() . '_img';
    $attr = array(
              'id'    => $id,
              'src'   => ""
    );
    if ($this->hasImage)
    {
      $attributes["src"] = $this->getOption('file_src');
    }
    else
    {
      $attributes["style"] = "display: none";
    }
    $tag = $this->renderTag(
          'img',
          array_merge($attr, $attributes )
    );

    return $tag.($this->hasImage ? "" : "<span>No image</span>");

  }

  private function getJCropJS() {
    $idStub = $this->getIdStub();
    $ratio = $this->getOption('image_ratio') ? 'aspectRatio: ' . $this->getOption('image_ratio') . ',' : '';
    $selectLink = $idStub."_select_link";
    $js = "
<script type=\"text/javascript\">
  var {$idStub}_api = null;
  function jcrop_{$idStub} () {
      {$idStub}_api = $.Jcrop('#{$idStub}_img', {
      $ratio
      setSelect: [$('#{$idStub}_x1').val(),
                  $('#{$idStub}_y1').val(),
                  $('#{$idStub}_x2').val(),
                  $('#{$idStub}_y2').val()
                  ],
      onChange: _jCropUpdateCoords" . ucfirst($idStub) . ",
      onSelect: _jCropUpdateCoords" . ucfirst($idStub) . "
    });
  }
  jQuery(document).ready(function(){
    var img = $('#{$idStub}_img');
    if (img.attr('src') != null && img.attr('src') != ''){
      img.show();
      jcrop_{$idStub}();
    }
  });
  function _jCropUpdateCoords" . ucfirst($idStub) . "(c) {
    jQuery('#{$idStub}_x1').val(c.x);
    jQuery('#{$idStub}_y1').val(c.y);
    jQuery('#{$idStub}_x2').val(c.x2);
    jQuery('#{$idStub}_y2').val(c.y2);
  }
  var currentFieldName = null;
  var currentFieldId = null;
  $('#{$selectLink}').click(function (){
    var popup = window.open('".url_for("cp_media_browser_select")."', 'assets', 'height=600,width=960,resizable=1' );
    var relatedInput = $(this).siblings('input[type=hidden]').eq(0);
    currentFieldName = relatedInput.attr('name');
    currentFieldId = relatedInput.attr('id');
  });
  function useAsset(fieldName, fieldId, full_url, crop_url)
  {
     var img = $('#{$idStub}_img');
    if ({$idStub}_api != null)
    {
      try {{$idStub}_api.destroy(); }catch (e){ }
    }
    $('#'+fieldId).val(full_url).siblings('img').attr('src', crop_url).show().siblings('span').hide();
    img.css('display', 'block');
    jcrop_{$idStub}();
    {$idStub}_api.release();

    _jCropUpdateCoords" . ucfirst($idStub) . " ({
      x : 0,
      y : 0,
      x2: img.width(),
      y2: img.height()
    });

  }
</script>
  ";

    return $js;
  }

  public function getIdStub(){
    if(!$this->idStub){
      $this->idStub = $this->setIdStub();
    }
    return $this->idStub;
  }

  protected function setIdStub() {
    $form = $this->getOption('form');
    $separator = '';

    $imageName = $this->getOption('image_field');
    $tableName = str_replace("[%s]", '', $form->getWidgetSchema()->getNameFormat());

    if ($form->getOption('embedded'))
    {
      $parentTableName = $form->getOption('parent_model')->getTable()->getTableName();

      $separator = 'embedded_' . $tableName . '_' . $tableName .
        '_' . $this->getOption('invoker')->getId();

      $idStub = $parentTableName . '_' . $separator . '_' . $imageName;
      $idStub = preg_replace('/\W/', '_', $idStub);
    }
    else
    {
      $idStub = $tableName . '_' . $imageName;
    }

    return $idStub;
  }
}
