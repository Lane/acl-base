<div class="add-form">
<h2><?php echo $friendlyTitle; ?></h2>
<p class="page-description">Fill out the form below to add a group</p>
<?php
	$inputs = array("fieldset" => false, "legend"=>false);
	$blacklist = array("fieldset", "created", "modified");
	echo $form->create("Group");
	echo $form->inputs($inputs, $blacklist);
	echo $form->end("Save");
?>
</div>