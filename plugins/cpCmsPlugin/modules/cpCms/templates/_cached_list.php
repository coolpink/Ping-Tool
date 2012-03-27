<?php

  $level = -1;
  $parent_id = null;
  foreach ($pager->getResults() as $i => $path)
  {
    $odd = fmod(++$i, 2) ? ' odd' : '';
    if ($path->getLevel() != $level)
    {
      $level = $path->getLevel();
      $node = $path->getNode();
      if ($node->hasParent())
      {
        $parent_id = $node->getParent()->getId();
      }else {
        $parent_id = null;
      }
    }
    include_partial("cpCms/list_tr", array("path"=>$path, "parent_id" => $parent_id, "helper"=>$helper));
  }