<?php

/**
 * @author     Artur Rozek
 * @version    1.0.0
 */
class sfWidgetFormDateJQueryUI extends sfWidgetForm
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   * @param string   culture           Sets culture for the widget
   * @param boolean  change_month      If date chooser attached to widget has month select dropdown, defaults to false
   * @param boolean  change_year       If date chooser attached to widget has year select dropdown, defaults to false
   * @param integer  number_of_months  Number of months visible in date chooser, defaults to 1
   * @param boolean  show_button_panel If date chooser shows panel with 'today' and 'done' buttons, defaults to false
   * @param string   theme             css theme for jquery ui interface, defaults to '/sfJQueryUIPlugin/css/ui-lightness/jquery-ui.css'
   * 
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {

    if(sfContext::hasInstance())
     $this->addOption('culture', sfContext::getInstance()->getUser()->getCulture());
    else
     $this->addOption('culture', "en");
    $this->addOption('change_month',  false);
    $this->addOption('change_year',  false);
    $this->addOption('date_format',  'dd/mm/yy');
    $this->addOption('minDate',  null);
    $this->addOption('maxDate',  "'+6m'");
    $this->addOption('number_of_months', 1);
    $this->addOption('show_button_panel',  false);
    $this->addOption('theme', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/themes/smoothness/jquery-ui.css');

    
    parent::configure($options, $attributes);
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $attributes = $this->getAttributes();

    $input = new sfWidgetFormInput(array(), $attributes);

    $html = $input->render($name, $value);

    $id = $input->generateId($name);
    $culture = $this->getOption('culture');
    $df = $this->getOption('date_format');
    $cm = $this->getOption("change_month") ? "true" : "false";
    $cy = $this->getOption("change_year") ? "true" : "false";
    $nom = $this->getOption("number_of_months");
    $minDate = $this->getOption("minDate");
    $maxDate = $this->getOption("maxDate");
    $sbp = $this->getOption("show_button_panel") ? "true" : "false";

    if ($culture!='en')
    {
    $html .= <<<EOHTML
<script type="text/javascript">
	$(function() {
    var params = $.datepicker.regional['$culture'];
    params.changeMonth = $cm;
    params.changeYear = $cy;
    params.numberOfMonths = $nom;
    params.dateFormat = '$df';
    params.showButtonPanel = $sbp;
    params.minDate = $minDate;
    params.maxDate = $maxDate;
    $("#$id").datepicker(params);
	});
</script>
EOHTML;
    }
    else
    {
    $html .= <<<EOHTML
<script type="text/javascript">
	$(function() {
    var params = {
    changeMonth : $cm,
    changeYear : $cy,
    numberOfMonths : $nom,
    dateFormat : '$df',
    minDate : $minDate,
    maxDate : $maxDate,
    showButtonPanel : $sbp };
    $("#$id").datepicker(params);
	});
</script>
EOHTML;
    }

    return $html;
  }

  /*
   *
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    $theme = $this->getOption('theme');
    return array($theme => 'screen');
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    $js = array();

    $culture = $this->getOption('culture');
    if ($culture!='en')
    {
      $js[] = '/sfJQueryUIPlugin/js/i18n/ui.datepicker-'.$culture.".js";
    }
    
    return $js;
  }

}
