<?php $c=0; //initialize counter ?>
<div id="users-index" class="content main-box">
	<h2><?php echo __("Users List"); ?></h2>
	<p class="page-description">
		<?php
		echo $paginator->counter(array(
			'format' => 'Page %page% of %pages%, showing records %start% - %end% of %count%'
		)); 
		?>
	</p>
	<div class="sort-users">
		<span class="sort-by"><?php __("Sort by:"); ?>
		<?php echo $paginator->sort('Username', 'User.username'); ?>
		<?php echo $paginator->sort('Group', 'Group.name'); ?>
		<?php echo $paginator->sort('Last Seen', 'User.modified'); ?>
		<?php echo $paginator->sort('Status', 'User.enabled'); ?>
	</div>
	<div class="inner-box">
		<ul class="user-list list">
			<?php foreach($users as $user): ?>
				<li class="user-list-item status<?php echo $user['User']['enabled']; ?>  alt<?php echo (($c++)%2)+1; ?>">
					<span class="user-list-item-name">
						<?php echo $user['User']['username']; ?>
					</span>
					<span class="user-list-item-group">
						(<?php echo Inflector::singularize($user['Group']['name']); ?>)
					</span>
					<?php if(!$user['User']['enabled']): ?>
					<span class="user-list-item-disabled">
						(Account Disabled)
					</span>
					<?php endif; ?>
					<span class="user-list-item-actions">
						<?php echo $html->link("edit", array(
							'controller' => 'users', 
							'action' => 'edit', 
							$user['User']['id']
						), array('class'=>'button-edit')); ?>
						<?php echo $html->link("delete", array(
							'controller' => 'users', 
							'action' => 'delete', 
							$user['User']['id']
						), array('class'=>'button-delete')); ?>
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