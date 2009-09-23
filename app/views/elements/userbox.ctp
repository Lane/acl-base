<?php
$in = array(
	'fieldset' => false,
	'username' => array('label' => 'Username:'),
	'passwd' => array('label' => 'Password:')
);
?>
<?php if(isset($User)): ?>
<h2>User</h2>
<div class="gravatar">
	<?php echo $gravatar->image($User['email_address'], array('size' => 50)); ?> 
</div>
<div class="user-info">
	<strong><?php echo $User['username']; ?></strong>
	<ul>
		<?php if(true): ?>
		<li><?php echo $html->link(__("Admin", true), array('controller' => 'admin', 'action' => 'dashboard'));?></li>
		<?php endif; ?>
		<li>
			<?php echo $html->link(__("My Account", true), array('controller' => 'users', 'action' => 'edit', $User['id']));?>
		</li>
		<li>
			<?php echo $html->link(__("Logout", true), array('controller' => 'users', 'action' => 'logout'));?>
		</li>
	</ul>
</div>
<?php else: ?>
<h2>Login</h2>
<?php echo $form->create('User', array('action'=>'login')); ?>
<?php echo $form->inputs($in); ?>
<?php echo $form->end('Login'); ?>
<?php endif; ?>
