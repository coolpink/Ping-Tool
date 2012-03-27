<?php

class buildTask extends sfDoctrineBuildTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('no-confirmation', null, sfCommandOption::PARAMETER_NONE, 'Whether to force dropping of the database'),
      new sfCommandOption('all', null, sfCommandOption::PARAMETER_NONE, 'Build everything and reset the database'),
      new sfCommandOption('all-classes', null, sfCommandOption::PARAMETER_NONE, 'Build all classes'),
      new sfCommandOption('model', null, sfCommandOption::PARAMETER_NONE, 'Build model classes'),
      new sfCommandOption('forms', null, sfCommandOption::PARAMETER_NONE, 'Build form classes'),
      new sfCommandOption('filters', null, sfCommandOption::PARAMETER_NONE, 'Build filter classes'),
      new sfCommandOption('sql', null, sfCommandOption::PARAMETER_NONE, 'Build SQL'),
      new sfCommandOption('db', null, sfCommandOption::PARAMETER_NONE, 'Drop, create, and either insert SQL or migrate the database'),
      new sfCommandOption('and-migrate', null, sfCommandOption::PARAMETER_NONE, 'Migrate the database'),
      new sfCommandOption('and-load', null, sfCommandOption::PARAMETER_OPTIONAL | sfCommandOption::IS_ARRAY, 'Load fixture data'),
      new sfCommandOption('and-append', null, sfCommandOption::PARAMETER_OPTIONAL | sfCommandOption::IS_ARRAY, 'Append fixture data'),
    ));

    $this->namespace = 'coolpink';
    $this->name = 'build';

    $this->briefDescription = 'Generate code based on your schema';

    $this->detailedDescription = <<<EOF
The [coolpink:build|INFO] task generates code based on your schema:

  [./symfony coolpink:build|INFO]

You must specify what you would like built. For instance, if you want model
and form classes built use the [--model|COMMENT] and [--forms|COMMENT] options:

  [./symfony coolpink:build --model --forms|INFO]

You can use the [--all|COMMENT] shortcut option if you would like all classes and
SQL files generated and the database rebuilt:

  [./symfony coolpink:build --all|INFO]

This is equivalent to running the following tasks:

  [./symfony coolpink:drop-db|INFO]
  [./symfony coolpink:build-db|INFO]
  [./symfony coolpink:build-model|INFO]
  [./symfony coolpink:build-forms|INFO]
  [./symfony coolpink:build-filters|INFO]
  [./symfony coolpink:build-sql|INFO]
  [./symfony coolpink:insert-sql|INFO]

You can also generate only class files by using the [--all-classes|COMMENT] shortcut
option. When this option is used alone, the database will not be modified.

  [./symfony coolpink:build --all-classes|INFO]

The [--and-migrate|COMMENT] option will run any pending migrations once the builds
are complete:

  [./symfony coolpink:build --db --and-migrate|INFO]

The [--and-load|COMMENT] option will load data from the project and plugin
[data/fixtures/|COMMENT] directories:

  [./symfony coolpink:build --db --and-migrate --and-load|INFO]

To specify what fixtures are loaded, add a parameter to the [--and-load|COMMENT] option:

  [./symfony coolpink:build --all --and-load="data/fixtures/dev/"|INFO]

To append fixture data without erasing any records from the database, include
the [--and-append|COMMENT] option:

  [./symfony coolpink:build --all --and-append|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {

    require_once(dirname(__FILE__).'/../MwbExporter/Core/SplClassLoader.php');
    $classLoader = new SplClassLoader();
    $classLoader->setIncludePath(dirname(__FILE__)."/../");
    $classLoader->register();
    $setup = array();
    $formatter = new \MwbExporter\Formatter\Doctrine1\Yaml\Loader($setup);

    // parse the mwb file
    $mwb = new \MwbExporter\Core\Workbench\Document(sfConfig::get('sf_data_dir').'/sql/model.mwb', $formatter);

    file_put_contents(sfConfig::get('sf_config_dir').'/doctrine/schema.yml', $mwb->display());

    $classLoader->unregister();

    if (!$mode = $this->calculateMode($options))
    {
      throw new InvalidArgumentException(sprintf("You must include one or more of the following build options:\n--%s\n\nSee this task's help page for more information:\n\n  php symfony help doctrine:build", join(', --', array_keys($this->getBuildOptions()))));
    }

    if (self::BUILD_DB == (self::BUILD_DB & $mode))
    {
      $task = new sfDoctrineDropDbTask($this->dispatcher, $this->formatter);
      $task->setCommandApplication($this->commandApplication);
      $task->setConfiguration($this->configuration);
      $ret = $task->run(array(), array('no-confirmation' => $options['no-confirmation']));

      if ($ret)
      {
        return $ret;
      }

      $task = new sfDoctrineBuildDbTask($this->dispatcher, $this->formatter);
      $task->setCommandApplication($this->commandApplication);
      $task->setConfiguration($this->configuration);
      $ret = $task->run();

      if ($ret)
      {
        return $ret;
      }

      // :insert-sql (or :migrate) will also be run, below
    }

    if (self::BUILD_MODEL == (self::BUILD_MODEL & $mode))
    {
      $task = new sfDoctrineBuildModelTask($this->dispatcher, $this->formatter);
      $task->setCommandApplication($this->commandApplication);
      $task->setConfiguration($this->configuration);
      $ret = $task->run();

      if ($ret)
      {
        return $ret;
      }
    }

    if (self::BUILD_FORMS == (self::BUILD_FORMS & $mode))
    {
      $task = new sfDoctrineBuildFormsTask($this->dispatcher, $this->formatter);
      $task->setCommandApplication($this->commandApplication);
      $task->setConfiguration($this->configuration);
      $ret = $task->run(array(), array("generator-class" => "cpGenerator"));

      if ($ret)
      {
        return $ret;
      }
    }

    if (self::BUILD_FILTERS == (self::BUILD_FILTERS & $mode))
    {
      $task = new sfDoctrineBuildFiltersTask($this->dispatcher, $this->formatter);
      $task->setCommandApplication($this->commandApplication);
      $task->setConfiguration($this->configuration);
      $ret = $task->run();

      if ($ret)
      {
        return $ret;
      }
    }

    if (self::BUILD_SQL == (self::BUILD_SQL & $mode))
    {
      $task = new sfDoctrineBuildSqlTask($this->dispatcher, $this->formatter);
      $task->setCommandApplication($this->commandApplication);
      $task->setConfiguration($this->configuration);
      $ret = $task->run();

      if ($ret)
      {
        return $ret;
      }
    }

    foreach ($this->loadModels() as $model)
    {
      if (Doctrine_Core::getTable($model)->hasTemplate("Cmsable"))
      {
        if (file_exists(sfConfig::get('sf_apps_dir')."/frontend/"))
        {
          if (!file_exists(sfConfig::get('sf_apps_dir')."/frontend/modules/".$model."/"))
          {
            passthru("php symfony coolpink:generate-module frontend ".$model);
          }
        }
      }
    }

    if ($options['and-migrate'])
    {
      $task = new sfDoctrineMigrateTask($this->dispatcher, $this->formatter);
      $task->setCommandApplication($this->commandApplication);
      $task->setConfiguration($this->configuration);
      $ret = $task->run();

      if ($ret)
      {
        return $ret;
      }
    }
    else if (self::BUILD_DB == (self::BUILD_DB & $mode))
    {
      $task = new sfDoctrineInsertSqlTask($this->dispatcher, $this->formatter);
      $task->setCommandApplication($this->commandApplication);
      $task->setConfiguration($this->configuration);
      $ret = $task->run();

      if ($ret)
      {
        return $ret;
      }
    }

    if (count($options['and-load']) || count($options['and-append']))
    {
      $task = new sfDoctrineDataLoadTask($this->dispatcher, $this->formatter);
      $task->setCommandApplication($this->commandApplication);
      $task->setConfiguration($this->configuration);

      if (count($options['and-load']))
      {
        $ret = $task->run(array(
          'dir_or_file' => in_array(array(), $options['and-load'], true) ? null : $options['and-load'],
        ));

        if ($ret)
        {
          return $ret;
        }
      }

      if (count($options['and-append']))
      {
        $ret = $task->run(array(
          'dir_or_file' => in_array(array(), $options['and-append'], true) ? null : $options['and-append'],
        ), array(
          'append' => true,
        ));

        if ($ret)
        {
          return $ret;
        }
      }
    }
  }
  protected function loadModels()
  {
    //Doctrine_Core::loadModels($this->getModelDirs());
    $models = Doctrine_Core::getLoadedModels();
    $models =  Doctrine_Core::initializeModels($models);
    $models = Doctrine_Core::filterInvalidModels($models);
    return $models;
  }
}
