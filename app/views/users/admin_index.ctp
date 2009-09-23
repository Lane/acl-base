<?php $c=0; //initialize counter ?>
<div id="users-index" class="content">
	<h2><?php echo __("Users List"); ?></h2>
	<?php foreach($users as $user): ?>
	<ul class="user-attributes list alt<?php echo (($c++)%2)+1; ?>">
		<li class="grid_3 username alpha"><?php echo $user['User']['username']; ?>
		<li class="grid_3 email"><?php echo $user['User']['email_address']; ?></li>
		<li class="grid_3 group"><?php echo $user['Group']['name']; ?></li>
		<li class="grid_3 actions omega">
			<?php echo $html->link("edit", array('controller' => 'users', 'action' => 'edit', $user['User']['id'])); ?>
			<?php echo $html->link("delete", array('controller' => 'users', 'action' => 'delete', $user['User']['id'])); ?>
		</li>
		<li class="clear"></li>
	</ul>
	<?php endforeach; ?>
</div>