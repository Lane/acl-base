<?php
	$inputs = array(
		'legend' => 'Please Login',
		'username',
		'passwd' => array(
			'label' => 'Password',
			'div' => 'input text required'
		)
	);
    echo $form->create('User', array('action' => 'login'));
	echo $form->inputs($inputs);
    echo $form->end('Login');
?>