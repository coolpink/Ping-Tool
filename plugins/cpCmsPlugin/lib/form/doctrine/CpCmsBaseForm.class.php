<?php

abstract class CpCmsBaseForm extends sfFormDoctrine {

  private $title_map;
  private $keywords_map;
  private $metadata_hidden = false;
  private $grouped_fields;
  public function setup()
  {
     unset($this["updated_at"], $this["created_at"], $this["path_id"], $this["version"]);

  }
  public function setTitleMap($fieldname){
    $this->title_map = $fieldname;
  }
  public function setKeywordsMap($fieldname){
    $this->keywords_map = $fieldname;
  }
  public function getTitleMap(){
    return $this->title_map;
  }
  public function getKeywordsMap(){
    return $this->keywords_map;
  }
  public function hideMetadata(){
    $this->metadata_hidden = true;
  }
  public function isMetadataHidden(){
    return $this->metadata_hidden;
  }
  public function setGroupedFields($fields)
  {
    $this->grouped_fields = $fields;
  }
  public function getGroupedFields()
  {
    return $this->grouped_fields;
  }
  public function getEmbeddedForm($name)
  {
  	return $this->embeddedForms[$name];
  }
  public function hasEmbeddedForm($name)
  {
  	return isset($this->embeddedForms[$name]);
  }
  public function saveEmbeddedForms($con = null, $forms = null)
  {
    if (null === $con)
    {
      $con = $this->getConnection();
    }

    if (null === $forms)
    {
      $forms = $this->embeddedForms;
    }

    foreach ($forms as $key=>$form)
    {
      if ($form instanceof sfFormObject)
      {
        unset($form[self::$CSRFFieldName]);
        $form->bindAndSave($this->taintedValues[$key], $this->taintedFiles, $con);
        $form->saveEmbeddedForms($con);
      }
      else
      {
        $this->saveEmbeddedForms($con, $form->getEmbeddedForms());
      }
    }

  }
  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if($this->getObject()->getId()>0){
      $taintedValues['id']=$this->getObject()->getId();
      $this->isNew = false;
    }
    parent::bind($taintedValues, $taintedFiles);
  }

}