<?php $c=0; //initialize counter ?>
<div id="groups-index" class="content">
	<h2><?php echo __("Group List"); ?></h2>
	<?php foreach($groups as $group): ?>
	<ul class="group-attributes list alt<?php echo (($c++)%2)+1; ?>">
		<li class="grid_3 name alpha"><?php echo $group['Group']['name']; ?>
		<li class="grid_1 users"><?php echo sizeof($group['User']); ?>&nbsp;</li>
		<li class="grid_6 users"><?php echo $group['Group']['description']; ?>&nbsp;</li>
		<li class="grid_2 actions omega">
			<?php echo $html->link("edit", array('controller' => 'groups', 'action' => 'edit', $group['Group']['id'])); ?>
			<?php echo $html->link("delete", array('controller' => 'groups', 'action' => 'delete', $group['Group']['id'])); ?>
		</li>
		<li class="clear"></li>
	</ul>
	<?php endforeach; ?>
</div>