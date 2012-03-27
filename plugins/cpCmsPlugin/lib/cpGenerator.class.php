<?php

class cpGenerator extends sfDoctrineFormGenerator
{
  public function getTheme()
  {
    return "cp";
  }

  public function getWidgetClassForColumn($column)
  {

    if ($json = $this->getJsonForColumn($column))
    {
      if (!empty($json->widget))
      {
        return $json->widget;
      }
    }
    return parent::getWidgetClassForColumn($column);
  }

  public function getLabelForColumn($column)
  {
    if ($json = $this->getJsonForColumn($column))
    {
      if (!empty($json->label))
      {
        return $json->label;
      }
    }
  }

  public function getHelpForColumn($column)
  {
    if ($json = $this->getJsonForColumn($column))
    {
      if (!empty($json->help))
      {
        return $json->help;
      }
    }
  }

  public function getGroupedJson()
  {
    $groups = array();
    foreach ($this->getColumns() as $column)
    {
      if ($json = $this->getJsonForColumn($column))
      {
        if (isset($json->group))
        {
          if (!isset($groups[$json->group]))
          {
            $groups[$json->group] = array();
          }
          if (!empty($json->group))
          {
            $groups[$json->group][] = $column->getFieldName();
          }
        }
      }
    }

    return count($groups) > 0 ? $this->convertArrayToString($groups) : null;
  }

  public function getTitleMap()
  {
    foreach ($this->getColumns() as $column)
    {
      if ($json = $this->getJsonForColumn($column))
      {
        if (!empty($json->titlemap))
        {
          return $column->getFieldName();
        }
      }
    }
    return null;
  }

  public function getWidgetOptionsForColumn($column)
  {
    if ($json = $this->getJsonForColumn($column))
    {
      if (!empty($json->options))
      {
        return $this->convertArrayToString($json->options);
      }
    }
    return parent::getWidgetOptionsForColumn($column);
  }

  private function convertArrayToString($arr)
  {
      $str = json_encode($arr);
      $str = str_replace("\/", "/", $str);
      $str = str_replace("{", "array (", $str);
      $str = str_replace("}", ")", $str);
      $str = str_replace("]", ")", $str);
      $str = str_replace("[", "array (", $str);
      $str = str_replace(":", " => ", $str);
      return $str;
  }

  protected function getJsonForColumn($column)
  {
    $json = null;

    if ($column->hasDefinitionKey("comment"))
    {

      $comment = $column->getDefinitionKey("comment");

      if (substr($comment, 0, 1) != "{")
      {
        $comment = "{".$comment."}";
      }

      $json = json_decode($comment);
    }

    return $json;
  }
}