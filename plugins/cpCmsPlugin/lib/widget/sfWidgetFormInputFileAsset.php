<?php

/*
 * This file is part of the cpMediaBrowser package.
 */

/**
 * sfWidgetFormInputFileAsset represents a media browser input tag based on sfWidgetFormInputFileEditable.
 *
 * @package    cpMediaBrowser
 * @subpackage widget
 * @author     Coolpink <dev@coolpink.net>
 */
class sfWidgetFormInputFileAsset extends sfWidgetFormInput
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * file_src:     The current image web source path (required)
   *  * edit_mode:    A Boolean: true to enabled edit mode, false otherwise
   *  * with_delete:  Whether to add a delete checkbox or not
   *  * delete_label: The delete label used by the template
   *  * template:     The HTML template to use to render this widget when in edit mode
   *                  The available placeholders are:
   *                    * %input% (the file upload widget)
   *                    * %delete% (the delete checkbox)
   *                    * %delete_label% (the delete label text)
   *                    * %file% (the file tag)
   *
   * In edit mode, this widget renders an additional widget named after the
   * file upload widget with a "_delete" suffix. So, when creating a form,
   * don't forget to add a validator for this additional field.
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->setOption('type', 'hidden');
    $this->addOption('edit_mode', true);
    $this->addOption('no_file_label', '<span>No file selected.</span>');
    $this->addOption('set_label', 'Choose a file');
    $this->addOption('with_replace', true);
    $this->addOption('replace_label', 'Choose a different file');
    $this->addOption('with_delete', true);
    $this->addOption('delete_label', 'Remove file');
    $this->addOption('template', '%file%<br />%input% %replace% %delete%<br /><br />');
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
    $input = parent::render($name, $value, $attributes, $errors);
    $_file = $this->getFileAsTag($value, $attributes);

    if (!$this->getOption('edit_mode'))
    {
      return $_file;
    }

    if (!$_file)
    {
      $setLabel = $this->translate($this->getOption('set_label'));
      $_image = $this->renderContentTag('div', $this->translate($this->getOption('no_file_label')), array('class' => 'selected_file'));
      $input = link_to($setLabel, '@cp_media_browser_select?type=file', array_merge($attributes, array('class' => 'mbp_form_select '))) . $input;
    }
    $_hidden = !$_file ? 'hidden' : '';

    if ($this->getOption('with_replace'))
    {
      $replaceName = ']' == substr($name, -1) ? substr($name, 0, -1).'_replace]' : $name.'_replace';

      $replaceLabel = $this->translate($this->getOption('replace_label'));
      $replace = link_to($replaceLabel, '@cp_media_browser_select?type=file', array_merge($attributes, array('name' => $replaceName, 'anchor' => 'replaceFile', 'class' => 'mbp_form_replace_file ' . $_hidden)));
    }
    else
    {
      $replace = '';
    }

    if ($this->getOption('with_delete'))
    {
      $deleteName = ']' == substr($name, -1) ? substr($name, 0, -1).'_delete]' : $name.'_delete';

      $deleteLabel = $this->translate($this->getOption('delete_label'));
      $delete = link_to($deleteLabel, 'assets/select', array_merge($attributes, array('name' => $deleteName, 'anchor' => 'removeFile', 'class' => 'mbp_form_delete_file ' . $_hidden)));
    }
    else
    {
      $delete = '';
      $deleteLabel = '';
    }

    if (!$_file) {
      $_file = $_image;
    }

    return strtr($this->getOption('template'), array('%input%' => $input, '%replace%' => $replace, '%delete%' => $delete, '%file%' => $_file));
  }

    /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
   public function getJavascripts()
   {
     return array('/cpCmsPlugin/js/widget.js');
   }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
   public function getStylesheets()
   {
     return array('/cpCmsPlugin/css/widget.css' => 'all');
   }

  protected function getFileAsTag($value, $attributes)
  {
    return false != $value ? $this->renderContentTag('div', $this->renderContentTag('span', $value, array('class' => 'selected_file')), array('class' => 'selected_file')) : '';
  }
}
