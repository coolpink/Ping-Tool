<div id="top">
<?php
$toolbars = "";
$tabs = '<ul class="tabs">';

foreach (sfConfig::get('app_cpAdminMenu_sections') as $key => $val){

  $toolbar = '<ul>';

  $selected = false;
  foreach ($val["groups"] as $group_key => $group_val){
    if (!isset($group_val["permission"]) || $sf_user->isSuperAdmin() || $sf_user->hasPermission($group_val["permission"])){
      $toolbar .= '<li>';
      $toolbar .= '<a href="#" class="label">'.$group_key.'</a>';
      $toolbar .= '<ul>';
      foreach ($group_val["items"] as $item_key => $item_val){

        if (!isset($item_val["permission"]) || $sf_user->isSuperAdmin() || $sf_user->hasPermission($item_val["permission"])){
          $selectedlink = false;
          if (isset($item_val["route"]))
          {
            $link = $item_val["route"];
          }
          else if (isset($item_val["module"]))
          {
            if (!isset($item_val["action"])){
              $item_val["action"] = "index";
            }
            if ($module == $item_val["module"] || (isset($item_val["additional_modules"]) && is_array($item_val["additional_modules"]) && in_array($module, $item_val["additional_modules"])))
            {
              $selected = true;
              $selectedlink = true;

            }
            $link = url_for($item_val["module"]."/".$item_val["action"]);
          }
          else if (isset($item_val["url"]))
          {
            $link = url_for($item_val["url"]);
          }
          $toolbar .= '<li><a href="'.$link.'" class="tt'.($selectedlink ? " selected":"").'" title="'.(isset($item_val["tooltip"]) ? ":".$item_key.": ".$item_val["tooltip"] : "").'"><span>'.image_tag(isset($item_val["icon"]) ? $item_val["icon"] : "").'<strong>'.$item_key.'</strong></span></a></li>';

        }
      }
      $toolbar .= '</ul>';
      $toolbar .= '</li>';
    }
  }
  $toolbar .= '  </ul>';
  $toolbar = '<div class="toolbar'.($selected ? " selected":"").'" id="toolbar_'.preg_replace('/[^a-z0-9_]/', '_', strtolower($key)).'">'.$toolbar;
  $toolbar .= '</div>';

  $tabs  .= '<li><a href="#" rel="#toolbar_'.preg_replace('/[^a-z0-9_]/', '_', strtolower($key)).'" class="'.($selected ? "selected":"").'"><span><span>'.$key.'</span></span></a></li>';

  $toolbars .= $toolbar;

}
$tabs .= '</ul>';
echo $tabs;
echo $toolbars;
?>

</div>