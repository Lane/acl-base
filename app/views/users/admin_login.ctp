<div class="main-box">
<h2><?php __("Login"); ?></h2>
<p class="page-description"><?php __("Please enter your username and password below"); ?></p>
<?php
	$inputs = array(
		'legend' => false,
		'fieldset' => false,
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
</div>