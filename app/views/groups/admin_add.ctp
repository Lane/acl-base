<div class="add-form grid_8">
<?php
	$blacklist = array("created", "modified");
	echo $form->create("Group");
	echo $form->inputs(null, $blacklist);
	echo $form->end("Save");
?>
</div>