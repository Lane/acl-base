<?php $c=0; //initialize counter ?>
<div id="groups-index" class="content main-box">
	<h2><?php echo __("Group List"); ?></h2>
	<p class="page-description">Viewing <?php echo sizeof($groups); ?> groups sorted by group name</p>
	<div class="inner-box">
		<ul class="group-list list">
		<?php foreach($groups as $group): ?>
			<li class="group-list-item alt<?php echo (($c++)%2)+1; ?>">
				<span class="group-list-item-name"><?php echo $group['Group']['name']; ?></span>
				<span class="group-list-item-users">(<?php echo sizeof($group['User']); ?> Users)</span>
				<span class="group-list-item-actions">
					<?php echo $html->link("edit", array('controller' => 'groups', 'action' => 'edit', $group['Group']['id'])); ?>
					<?php echo $html->link("delete", array('controller' => 'groups', 'action' => 'delete', $group['Group']['id'])); ?>
				</span>
				<span class="group-list-item-description"><?php echo $group['Group']['description']; ?></span>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>