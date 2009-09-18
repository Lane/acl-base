<div class="add-form grid_8 alpha">
<?php
	$inputs = array(
		'username', 
		'email_address',
		'passwd' => array(
			'label' => 'Password',
			'div' => 'input text required'
		),
		'Group',
		'enabled'
	);
	echo $form->create("User");
	echo $form->inputs($inputs);
	echo $form->end("Save");
?>
</div>