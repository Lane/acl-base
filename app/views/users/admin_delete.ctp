<div class="delete-form">
<h2>Delete this user?</h2>
<div class="user-username label grid_3 alpha">Username:</div>
<div class="user-username value grid_9 omega"><?php echo $user['User']['username']; ?></div>
<div class="user-email label grid_3 alpha">E-mail Address:</div>
<div class="user-email value grid_9 omega"><?php echo $user['User']['email_address']; ?></div>
<div class="user-group label grid_3 alpha">Group:</div>
<div class="user-group value grid_9 omega"><?php echo $user['Group']['name']; ?></div>
<div class="user-created label grid_3 alpha">Created:</div>
<div class="user-created value grid_9 omega"><?php echo $user['User']['created']; ?></div>
<?php
	echo $form->create('User', array('action'=>'delete'));
	echo $form->input('User.id', array('type'=>'hidden', 'value'=>$user['User']['id']));
	echo $form->input('User.delete', array('type'=>'hidden', 'value'=>1));
	echo $form->end("Yes");
	echo $html->link("Cancel", array('controller' => 'users', 'action' => 'index'));
?>
</div>