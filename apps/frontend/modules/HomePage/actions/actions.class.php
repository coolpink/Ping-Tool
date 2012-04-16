<?php

/**
 * HomePage actions.
 *
 * @package    Domain Checker
 * @subpackage HomePage
 * @author     Dave Walker
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class HomePageActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->clients = Doctrine_Core::getTable("Client")->findAllOrdered();
    $this->projects = Doctrine_Core::getTable("Project")->findAll();

    $this->check_count = Doctrine_Core::getTable("Check")->count();
    /*$this->checks = Doctrine_Core::getTable("Check")->findAll();*/
    $this->upTime =  Doctrine_Core::getTable("Check")->getUptime();
  }

  /**
   * execute a domain check
   * @param sfWebRequest $request
   * @return sfView
   */
  public function executeCheckDomain(sfWebRequest $request)
  {
    if ($domain = $request->getPostParameter("domain"))
    {
      if ($project = Doctrine_Core::getTable("project")->findOneByDomain($domain))
      {
        $data["msg"] = "OK";
        $data["check_count"] = Doctrine_Core::getTable("Check")->findAll()->count();
        $data["status"] = $project->getStatus();
      }
    }
    return $this->renderText(json_encode($data));
  }

  public function executePingDomains(sfWebRequest $request)
  {
    // load in all projects (domains)
    if ($projects = Doctrine_Core::getTable("project")->findAll())
    {
      foreach ($projects as $project)
      {
        if ($project->getStatus() !== "OK")
        {
          // there must be some issue getting the domain to load, we need to alert the project team for this project
          if ($team = $project->getProjectTeam())
          {
            $mailto = array();
            foreach ($team as $member)
            {
              $mailto[$member->getEmail()] = $member->getName();
            }
            $message = $this->getMailer()->compose(
              "ping@coolpink.net",
              $mailto,
              "Site alert: {$project->getDomain()}",
              ""
            );
            $this->getMailer()->send($message);

            echo "{$project->getDomain()} is inaccessible, informing project team\n";
          }

        }
      }
    }
    die("Scan completed successfully");
  }
}
