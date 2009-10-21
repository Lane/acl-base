<div class="add-form">
	<h2><?php echo $friendlyTitle; ?></h2>
	<p class="page-description">
		<?php __("Fill out the form below and click Save to modify the user."); ?>
	</p>
	<?php
		$inputs = array(
			'fieldset' => false,
			'legend' => false,
			'User.username', 
			'User.email_address',
			'User.passwd' => array(
				'label' => 'Password',
				'div' => 'input text required'
			),
			'User.Group' => array('value' => $this->data['User']['group_id']),
			'User.enabled'
		);

		echo $form->create("User");
		if(isset($this->data['User']['group_id']))
		{
			echo $form->input('User.id', array('type' => 'hidden'));
			echo $form->input('User.old_group_id', array(
				'type' => 'hidden', 
				'value' => $this->data['User']['group_id']
			));
		} 
		echo $form->inputs($inputs);
		echo $form->end("Save");
	?>
	<?php if(strpos($this->action, 'edit') !== false): ?>
	<?php echo $this->element("permissions"); ?>
	<?php endif; ?>
	
</div>