<?php

require_once dirname(__FILE__).'/../lib/cpCmsGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/cpCmsGeneratorHelper.class.php';

/**
 * cms actions.
 *
 * @package    awesomeadmin
 * @subpackage cms
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cpCmsActions extends autocpCmsActions
{
  protected function addSortQuery($query)
  {
    //don't allow sorting; always sort by tree and lft
    $query->addOrderBy('root_id, lft');
  }

  public function executeBatch(sfWebRequest $request)
  {
    if ("batchOrder" == $request->getParameter('batch_action'))
    {
      return $this->executeBatchOrder($request);
    }

    parent::executeBatch($request);
  }

    public function executeMove(sfWebRequest $request){
        Header("Content-type: application/json");
        $elm   = Doctrine::getTable('Path')->find($request->getParameter("elm"));
        $to = Doctrine::getTable('Path')->find($request->getParameter("to"));
        if ($elm->getObject()->canUserMove($this->getUser())){
        	$elm->getNode()->moveAsFirstChildOf($to);
        	echo json_encode(array("success" => true, "elm" => $elm->getId(), "to" => $to->getId()));
        }else {
        	echo json_encode(array("success" => false, "elm" => $elm->getId(), "to" => $to->getId()));
        }
        exit;
    }
   public function executeInsertAfter(sfWebRequest $request){
        Header("Content-type: application/json");

        $elm   = Doctrine::getTable('Path')->find($request->getParameter("elm"));
        $to = Doctrine::getTable('Path')->find($request->getParameter("to"));
        $elm->getNode()->moveAsNextSiblingOf($to);
        echo json_encode(array("success" => true, "elm" => $elm->getId(), "to" => $to->getId()));
        exit;
  }
  public function executeInsertBefore(sfWebRequest $request){
        Header("Content-type: application/json");

        $elm   = Doctrine::getTable('Path')->find($request->getParameter("elm"));
        $to = Doctrine::getTable('Path')->find($request->getParameter("to"));
        $elm->getNode()->moveAsPrevSiblingOf($to);
        echo json_encode(array("success" => true, "elm" => $elm->getId(), "to" => $to->getId()));
        exit;
  }

  public function executeBatchOrder(sfWebRequest $request)
  {
    $newparent = $request->getParameter('newparent');

    //manually validate newparent parameter

    //make list of all ids
    $ids = array();
    foreach ($newparent as $key => $val)
    {
      $ids[$key] = true;
      if (!empty($val))
        $ids[$val] = true;
    }
    $ids = array_keys($ids);

    //validate if all id's exist
    $validator = new sfValidatorDoctrineChoiceMany(array('model' => 'Path'));
    try
    {
      // validate ids
      $ids = $validator->clean($ids);

      // the id's validate, now update the tree
      $count = 0;
      $flash = "";

      foreach ($newparent as $id => $parentId)
      {
        if (!empty($parentId))
        {
          $node   = Doctrine::getTable('Path')->find($id);
          $parent = Doctrine::getTable('Path')->find($parentId);

          if (!$parent->getNode()->isDescendantOfOrEqualTo($node))
          {
            $node->getNode()->moveAsFirstChildOf($parent);
            $node->save();

            $count++;

            $flash .= "<br/>Moved '".$node['name']."' under '".$parent['name']."'.";
          }
        }
      }

      if ($count > 0)
      {
        $this->getUser()->setFlash('notice',
          sprintf("Content order updated, moved %s item%s:".$flash,
            $count, ($count > 1 ? 's' : '')));
      }
      else
      {
        $this->getUser()->setFlash('error', "You must at least move one item to update the Content order");
      }
    }
    catch (sfValidatorError $e)
    {
      $this->getUser()->setFlash('error', 'Cannot update the Content order, maybe some item are deleted, try again');
    }

    $this->redirect('@path');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object',
                         array('object' => $this->getRoute()->getObject())));

    $object = $this->getRoute()->getObject();
    if ($object->getObject()->canUserDelete($this->getUser()))
    {
		if ($object->getNode()->isValidNode())
		{
		  $object->getNode()->delete();
		}
		else
		{
		  $object->delete();
		}
    	$this->getUser()->setFlash('notice', 'The item was deleted successfully.');
    }


    $this->redirect('@path');
  }

  public function executeListNew(sfWebRequest $request)
  {
    $this->executeNew($request);
    $this->form->setDefault('parent_id', $request->getParameter('id'));
    $this->setTemplate('edit');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {

      $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
      if ($form->isValid())
      {
        $this->getUser()->setFlash('notice', $form->getObject()->isNew() ?
                                                'The item was created successfully.' :
                                                'The item was updated successfully.');

        $tree = $form->save();

        $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $tree)));

        if ($request->hasParameter('_save_and_add'))
        {
          $this->getUser()->setFlash('notice', $this->getUser()->getFlash('notice').' You can add another one below.');

          /*
            $this->redirect('@tree_new');
          */
        }
        else
        {

            $this->redirect(array('sf_route' => 'path_edit', 'sf_subject' => $tree));

        }
      }
      else
      {

        $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.');
      }

  }
}
