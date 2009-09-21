<div class="add-form grid_8 alpha">
<?php
	$inputs = array(
		'User.username', 
		'User.email_address',
		'User.passwd' => array(
			'label' => 'Password',
			'div' => 'input text required'
		),
		'User.Group',
		'User.enabled' => array('checked' => true)
	);
	echo $form->create("User");
	if(isset($this->data['User']['group_id']))
	{
		echo $form->input('User.id', array('type' => 'hidden'));
		echo $form->input('User.old_group_id', array('type' => 'hidden', 'value' => $this->data['User']['group_id']));
	} 
	echo $form->inputs($inputs);
	echo $form->end("Save");
?>
</div>