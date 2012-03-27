<td style="white-space: nowrap;">
  <ul class="sf_admin_td_actions" id="actions_<?php echo $path->getId(); ?>">
  <?php
  $t_type = $path->getTemplateType();
  $relatedObj = $path->getObject();
  $allowedChildren = $relatedObj->getAllowedChildren();
  if (count($allowedChildren) > 0){
  ?>
    <li class="sf_admin_action_add"><a href="javascript:void(0);" class="add-new-button"><span>Add New</span></a>
      <script type="text/javascript">
        allowedChildren[<?php echo $path->getId(); ?>] = [];
        childPageTypes[<?php echo $path->getId(); ?>] = [];
        <?php foreach ($allowedChildren as $child){
        $tmpChild = new $child();
        if ($tmpChild->canUserCreate($user->getRawValue())){
			?>
			allowedChildren[<?php echo $path->getId(); ?>].push({
				  'label'		  : '<?php echo Doctrine_Core::getTable("Path")->getFormattedTemplateType($child); ?>',
				  'icon' 		  : '',
				  'position' 	: 'left',
				  'callback'	: function (e){},
				  'children'  : [],
				  'href'      : '<?php echo url_for("cpCms/new"); ?>?type=<?php echo $child; ?>&parent=<?php echo $path->getId(); ?>'
			  });
			  childPageTypes[<?php echo $path->getId(); ?>].push('<?php echo $child; ?>');
			<?php
			}
        } ?>
      </script>
    </li>
  <?php
  }else {
      ?>
      <li class="sf_admin_action_add disabled"><a href="javascript:void(0);" class="add-new-button"><span>Add New</span></a>
    <?php
  }
 ?>

  <?php
  if ($relatedObj->canUserEdit($user->getRawValue()))
  {
  	echo "<script type='text/javascript'>canUserEdit[".$path->getId()."] = true;</script>";
  	echo $helper->linkToEdit($path, array(  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',));
  }
  if ($relatedObj->canUserDelete($user->getRawValue()))
  {
  	echo "<script type='text/javascript'>canUserDelete[".$path->getId()."] = true;</script>";
	echo $helper->linkToDelete($path, array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',));
  }
   ?>
  </ul>

</td>
