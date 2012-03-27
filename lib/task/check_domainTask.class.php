<?php

class check_domainTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'coolpink';
    $this->name             = 'check-domains';
    $this->briefDescription = 'A quick check of all URL statuses';
    $this->detailedDescription = <<<EOF
The [check_domain|INFO] task does things.
Call it with:

  [php symfony check_domain|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();


    $projects = Doctrine_Core::getTable("project")->findAll();
    $this->log("Checking domain statuses... {$projects->count()} domains found");
    foreach ($projects as $project)
    {
      $this->log("Checking {$project->getDomain()}..... {$project->getStatus()}");
      if ($project->getStatus() !== "OK")
      {
        $team = $project->getProjectTeam();
        $this->log("Notifying {$project->getDomain()} project team... {$team->count()} member(s)");

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
      }
    }
    $this->log("Done");
  }
}
