<?php $c=0; //initialize counter ?>
<div id="groups-index" class="content main-box">
	<h2><?php echo __("Group List"); ?></h2>
	<div id="group-list">
	<p class="page-description">
		<?php
		echo $paginator->counter(array(
			'format' => 'Page %page% of %pages%, showing records %start% - %end% of %count%'
		)); 
		?>
		<?php echo $paginator->sort('Name', 'Group.name'); ?>
	</p>
	<div class="inner-box">
		<ul class="group-list list">
		<?php foreach($groups as $group): ?>
			<li class="group-list-item alt<?php echo (($c++)%2)+1; ?>">
				<span class="group-list-item-name">
					<?php echo $group['Group']['name']; ?>
				</span>
				<span class="group-list-item-users">
					(<?php echo sizeof($group['User']); ?> Users)
				</span>
				<span class="group-list-item-actions">
					<?php echo $html->link("edit", array(
						'controller' => 'groups', 
						'action' => 'edit', 
						$group['Group']['id']
						), array('class'=>'button-edit')); ?>
					<?php echo $html->link("delete", array(
						'controller' => 'groups', 
						'action' => 'delete', 
						$group['Group']['id']
					), array('class'=>'button-delete')); ?>
				</span>
				<span class="group-list-item-description">
					<?php echo $group['Group']['description']; ?>
				</span>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
	</div>
</div>