<?php

  $type = $path->getTemplateType();
  $tmpObj = $path->getObject();
  $user = $sf_context->getUser();
  $node = $path->getNode();

?>
<tr rel="<?php
  echo $path["id"];
?>" cmsdraggable="<?php
echo $tmpObj->canUserMove($sf_user->getRawValue()) ? "true" : "false";
?>" cmseditable="<?php
echo $tmpObj->canUserEdit($sf_user->getRawValue()) ? "true" : "false";
?>" id="node-<?php echo $path['id'] ?>" class="sf_admin_row<?php
// insert hierarchical info

if ($node->isValidNode() && $parent_id != null)
{
    echo " child-of-node-".$parent_id;
}
?>">
<script type="text/javascript">
    pageTypes[<?php echo $path['id']; ?>] = '<?php echo $path->getTemplateType(); ?>';
</script>
<?php include_partial('cpCms/list_td_tabular', array('path' => $path, 'user'=>$user)) ?>

<?php include_partial('cpCms/list_td_actions', array('path' => $path, 'helper' => $helper, 'user'=>$user)) ?>
</tr>