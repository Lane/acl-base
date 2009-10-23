<div class="add-form">
	<h2><?php echo $friendlyTitle; ?></h2>
	<p class="page-description">Fill out the form below to add a group</p>
	<?php
		$inputs = array(
			"fieldset" => false, 
			"legend"=>false,
			"Group.name",
			"Group.description"
		);
		$blacklist = array("fieldset", "created", "modified");
		echo $form->create("Group");
		echo $form->inputs($inputs, $blacklist);
		echo $form->end("Save");
	?>
	<?php if(strpos($this->action, 'edit') !== false): ?>
	<?php echo $this->element("permissions"); ?>
	<?php endif; ?>
</div>