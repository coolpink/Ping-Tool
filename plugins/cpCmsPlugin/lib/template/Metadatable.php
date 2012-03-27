<?php

class Doctrine_Template_Metadatable extends Doctrine_Template
{
  protected $_options = array();
  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }
  public function setTableDefinition()
  {

    $this->hasColumn('meta_page_title', 'string', 255);
    $this->hasColumn('meta_navigation_title', 'string', 255);
    $this->hasColumn('meta_path', 'string', 255);
    $this->hasColumn('meta_keywords', 'clob', 65535);
    $this->hasColumn('meta_description', 'clob', 65535);
    $this->hasColumn('meta_visible_in_navigation', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => true
             ));
  }
}
?>