<div class="add-form">
<h2><?php echo $friendlyTitle; ?></h2>
<p class="page-description">Fill out the form below to add a group</p>
<?php
	$inputs = array("fieldset" => false, "legend"=>false);
	$blacklist = array("fieldset", "created", "modified");
	echo $form->create("Group");
	echo $form->inputs($inputs, $blacklist);
?>
<h2>Permissions</h2>
<div class="permissions-heading">
	<span class="permissions-heading-module">Module</span>
	<span class="permissions-heading-create">Create</span>
	<span class="permissions-heading-read">Read</span>
	<span class="permissions-heading-update">Update</span>
	<span class="permissions-heading-delete">Delete</span>
	<span class="permissions-heading-actions">&nbsp;</span>
</div>
<?php
	echo $form->end("Save");
?>
</div>