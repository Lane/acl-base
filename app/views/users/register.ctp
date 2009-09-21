<?php
	$inputs = array(
		'legend' => 'Register',
		'username' => array(
			'after' => '<span>Must be between 6-12 characters with no spaces</span>'
		), 
		'email_address' => array(
			'after' => '<span>Valid email address required</span>'
		),  
		'password' => array(
			'after' => '<span>Must be at least 8 characters</span>'
		), 
		'confirm_password' => array(
			'type' => 'password',
			'div' => 'input text required'
		)
	);
    echo $form->create('User', array('action' => 'register'));
    echo $form->inputs($inputs);
    echo $form->end('Register');
?>