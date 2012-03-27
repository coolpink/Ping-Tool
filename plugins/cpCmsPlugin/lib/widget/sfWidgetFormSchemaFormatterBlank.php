<?php

class sfWidgetFormSchemaFormatterBlank extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat       = "<div class=\"form-row\">\n  <span class=\"form-label\">%label%</span>\n
    <div class=\"form-field\">%error%%field%%help%%hidden_fields%</div>\n</div>\n",
    $errorRowFormat  = "<div class=\"form-row\"><div class=\"form-errors\">\n%errors%</div></div>\n";
}
