<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormTextareaTinyMCE represents a Tiny MCE widget.
 *
 * You must include the Tiny MCE JavaScript file by yourself.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormTextareaTinyMCE.class.php 17192 2009-04-10 07:58:29Z fabien $
 */
class htmleditor extends sfWidgetFormTextareaTinyMCE
{
  protected function configure($options = array(), $attributes = array())
  {

    $this->addOption('content_css');
    parent::configure($options, $attributes);
  }
  /**
   * @param  string $name        The element name
   * @param  string $value       The value selected in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
    $textarea = sfWidgetFormTextarea::render($name, $value, $attributes, $errors);
    $useTheme = $this->getOption("theme");
    $useConfig = $this->getOption("config");
    $useCSS = $this->getOption("content_css");

    if ($useTheme == "simple"){
        $useTheme = "advanced";
        if (!empty($useConfig)){
            $useConfig .= ",\n";
        }
        $useConfig .= "theme_advanced_buttons1: 'bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink,anchor,cleanup,removeformat,image',
                    theme_advanced_buttons2 : '',
                    theme_advanced_buttons3 : '',
                    theme_advanced_path : false";

    } elseif ($useTheme == "table"){
        $useTheme = "advanced";
        if (!empty($useConfig)){
            $useConfig .= ",\n";
        }
        $useConfig .= "theme_advanced_buttons1: 'bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,tablecontrols,bullist,numlist,undo,redo,link,unlink,anchor,cleanup,removeformat,image,code',
                    theme_advanced_buttons2 : '',
                    theme_advanced_buttons3 : '',
                    theme_advanced_path : false";

    } elseif ($useTheme == "table-advanced"){
        $useTheme = "advanced";
        if (!empty($useConfig)){
            $useConfig .= ",\n";
        }
        $useConfig .= "theme_advanced_buttons1: 'formatselect,fontsizeselect,forecolor,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist',
                    theme_advanced_buttons2 : 'tablecontrols,undo,redo,link,unlink,anchor,cleanup,removeformat,image,code',
                    theme_advanced_buttons3 : '',
                    theme_advanced_path : false";
    } else {
        $useConfig .= "theme_advanced_buttons1: 'formatselect,separator,bold,italic,underline,separator,paste,pasteword,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,table,row_props,cell_props,bullist,numlist,undo,redo,anchor,link,unlink,anchor,cleanup,removeformat,image,,sepearator,media,code',
                    theme_advanced_buttons2 : '',
                    theme_advanced_buttons3 : '',
                    theme_advanced_path : false";
    }
    if (!empty($useCSS)){
        $useConfig .= ",\ncontent_css : '".$useCSS."'";
    }
    $js = sprintf(<<<EOF
<script type="text/javascript">
  cpMediaBrowserWindowManager.init('%s');
  tinyMCE.init({
    file_browser_callback: "cpMediaBrowserWindowManager.tinymceCallback",
    mode:                              "exact",
    elements:                          "%s",
    theme:                             "%s",
    plugins :                          "paste,style,media,table",
    %s
    %s
    theme_advanced_toolbar_location:   "top",
    theme_advanced_toolbar_align:      "left",
    theme_advanced_statusbar_location: "bottom",
    theme_advanced_resizing:           true,
    skin:                              'o2k7',
	valid_elements : "@[id|class|style|title|dir<ltr?rtl|lang|xml::lang|onclick|ondblclick|"
	+ "onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|"
	+ "onkeydown|onkeyup],a[rel|rev|charset|hreflang|tabindex|accesskey|type|"
	+ "name|href|target|title|class|onfocus|onblur],strong/b,em/i,strike,u,"
	+ "#p,-ol[type|compact],-ul[type|compact],-li,br,img[longdesc|usemap|"
	+ "src|border|alt=|title|hspace|vspace|width|height|align],-sub,-sup,"
	+ "-blockquote,-table[border=0|cellspacing|cellpadding|width|frame|rules|"
	+ "height|align|summary|bgcolor|background|bordercolor],-tr[rowspan|width|"
	+ "height|align|valign|bgcolor|background|bordercolor],tbody,thead,tfoot,"
	+ "#td[colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor"
	+ "|scope],#th[colspan|rowspan|width|height|align|valign|scope],caption,div,"
	+ "-span,-code,-pre,address,-h1,-h2,-h3,-h4,-h5,-h6,hr[size|noshade],-font[face"
	+ "|size|color],dd,dl,dt,cite,abbr,acronym,del[datetime|cite],ins[datetime|cite],"
	+ "object[classid|width|height|codebase|*],param[name|value|_value],embed[type|width"
	+ "|height|src|*],script[src|type],map[name],area[shape|coords|href|alt|target],bdo,"
	+ "button,col[align|char|charoff|span|valign|width],colgroup[align|char|charoff|span|"
	+ "valign|width],dfn,fieldset,form[action|accept|accept-charset|enctype|method],"
	+ "input[accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value],"
	+ "kbd,label[for],legend,noscript,optgroup[label|disabled],option[disabled|label|selected|value],"
	+ "q[cite],samp,select[disabled|multiple|name|size],small,"
	+ "textarea[cols|rows|disabled|name|readonly],tt,var,big,iframe[align<bottom?left?middle?right?top|class|frameborder|height|id"
    +"|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style"
    +"|title|width]",
    relative_urls:                     false
    %s
  });
</script>
EOF
    ,
      url_for('cp_media_browser_widget'),
      $this->generateId($name),
      $useTheme,
      $this->getOption('width')  ? sprintf('width:                             "%s'.(substr_count($this->getOption('width'),'%') > 0 ? '' : 'px').'",', $this->getOption('width')) : '',
      $this->getOption('height') ? sprintf('height:                            "%s'.(substr_count($this->getOption('height'),'%') > 0 ? '' : 'px').'",', $this->getOption('height')) : '',
      !empty($useConfig) ? ",\n".$useConfig : ''
    );

    return $textarea.$js;
  }
}
