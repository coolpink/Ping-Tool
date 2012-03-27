<?php

/**
 * PluginPath form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginPathForm extends BasePathForm
{
   protected $parentId = null;
   public $embededModel = "";
   protected $embededFormName = "";
   private $grouped_fields;
   public function setupInheritance()
   {
        unset($this['root_id'], $this['lft'], $this['rgt'], $this['level'], $this['created_at'], $this['updated_at'], $this['object_id'], $this["template_type"]);


        $this->setDefault('visible_in_navigation', true);
        if ($this->isNew()){
            $this->embededModelName = sfContext::getInstance()->getRequest()->getParameter("type", "ContentPage");
            $this->parent = sfContext::getInstance()->getRequest()->getParameter("parent");
          
            $this->embededModel = new $this->embededModelName();
        } else {
           $this->embededModelName = $this->getObject()->getTemplateType();
           $this->embededModel = Doctrine::getTable($this->embededModelName)->find($this->getObject()->getObjectId());
           $parentNode = $this->getObject()->getNode()->getParent();
           $this->parent = $parentNode != null ? $parentNode->getId() : null;
        }


        $this->embdedFormName = $this->embededModelName."Form";
         

        $embeddedForm = new $this->embdedFormName($this->embededModel);

		$this->grouped_fields = $embeddedForm->getGroupedFields();

        $embeddedForm->getWidgetSchema()->setFormFormatterName('list');

        if ($embeddedForm->isMetadataHidden())
        {

          $this->widgetSchema["meta_page_title"] = new sfWidgetFormInputHidden();
          $this->widgetSchema["meta_navigation_title"] = new sfWidgetFormInputHidden();
          $this->widgetSchema["meta_path"] = new sfWidgetFormInputHidden();
          $this->widgetSchema["meta_keywords"] = new sfWidgetFormInputHidden();
          $this->widgetSchema["meta_description"] = new sfWidgetFormInputHidden();

        }

        $this->embedForm('Content', $embeddedForm);
        $this->widgetSchema['parent'] = new sfWidgetFormInputHidden();
        $this->validatorSchema['parent'] = new sfValidatorInteger(array(
            'required' => false
        ));
        $this->widgetSchema['template_type'] = new sfWidgetFormInputHidden();
        $this->validatorSchema['template_type'] = new sfValidatorString(array(
            'required' => false
        ));
        $this->setDefault('parent', $this->parent);
        $this->setDefault('template_type', $this->embededModelName);

    }

    public function getGroupedFields()
    {
		return $this->grouped_fields;
    }

    public function updateParentColumn($parentId)
    {
        $this->parent = $parentId;
        // further action is handled in the save() method
    }
    public function getErrors()
     {
       $errors = array();

       // individual widget errors
       foreach ($this as $form_field)
       {
         if ($form_field->hasError())
         {
           $error_obj = $form_field->getError();
           if ($error_obj instanceof sfValidatorErrorSchema)
           {
             foreach ($error_obj->getErrors() as $error)
             {
               // if a field has more than 1 error, it'll be over-written
               $errors[$form_field->getName()] = $error->getMessage();
             }
           }
           else
           {
             $errors[$form_field->getName()] = $error_obj->getMessage();
           }
         }
       }

       // global errors
       foreach ($this->getGlobalErrors() as $validator_error)
       {
         $errors[] = $validator_error->getMessage();
       }

       return $errors;
    }
    protected function doSave($con = null)
    {

        parent::doSave($con);
        $this->getEmbeddedForm("Content")->getObject()->save();
        $embeddedObject = $this->getEmbeddedForm("Content")->getObject();
        $this->object->setObjectId($embeddedObject->getId());
        $this->object->save($con);

        $embeddedObject->setPathId($this->object->getId());
        $embeddedObject->save($con);


        $node = $this->object->getNode();

        if ($this->parent != $this->object->getParent() || !$node->isValidNode())
        {
            if (empty($this->parent))
            {
                //save as a root
                if ($node->isValidNode())
                {
                    $node->makeRoot($this->object['id']);
                    $this->object->save($con);
                }
                else
                {
                    $this->object->getTable()->getTree()->createRoot($this->object); //calls $this->object->save internally
                }
            }
            else
            {
                //form validation ensures an existing ID for $this->parentId
                $parent = $this->object->getTable()->find($this->parent);
                $method = ($node->isValidNode() ? 'move' : 'insert') . 'AsLastChildOf';
                $node->$method($parent); //calls $this->object->save internally
            }
        }
    }
}
