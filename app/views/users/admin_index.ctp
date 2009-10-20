<?php $c=0; //initialize counter ?>
<div id="users-index" class="content main-box">
	<h2><?php echo __("Users List"); ?></h2>
	<p class="page-description">Viewing <?php echo sizeof($users);?> users by time last seen </p>
	<div class="inner-box">
		<ul class="user-list list">
			<?php foreach($users as $user): ?>
				<li class="user-list-item  alt<?php echo (($c++)%2)+1; ?>">
					<span class="user-list-item-name">
						<?php echo $user['User']['username']; ?>
					</span>
					<span class="user-list-item-group">
						(<?php echo Inflector::singularize($user['Group']['name']); ?>)
					</span>
					<span class="user-list-item-actions">
						<?php echo $html->link("edit", array(
							'controller' => 'users', 
							'action' => 'edit', 
							$user['User']['id']
						)); ?>
						<?php echo $html->link("delete", array(
							'controller' => 'users', 
							'action' => 'delete', 
							$user['User']['id']
						)); ?>
					</span>
					<span class="user-list-item-lastseen">
						<?php __("Last seen"); ?> 
						<?php echo $time->timeAgoInWords($user['User']['modified']); ?>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>