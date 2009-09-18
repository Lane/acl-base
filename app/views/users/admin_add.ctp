<div class="add-form grid_8 alpha">
<?php
	$blacklist = array("created", "modified");
	echo $form->create("User");
	echo $form->inputs(null, $blacklist);
	echo $form->end("Create");
?>
</div>