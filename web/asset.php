<?php

    require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

    $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);

    require_once(dirname(__FILE__).'/../plugins/cpCmsPlugin/lib/asset.php');
?>
