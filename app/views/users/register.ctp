<?php
    echo $form->create('User', array('action' => 'register'));
    echo $form->input('username');
	echo $form->input('email_address');
	echo $form->input('password');
	echo $form->input('confirm_password', array('type' => 'password', 'div' => 'required'));
    echo $form->end('Register');
?>