<div class="groups-delete">
<h2>Delete this group?</h2>
<div class="group-name label grid_3 alpha">Name:</div>
<div class="group-name value grid_9 omega"><?php echo $group['Group']['name']; ?></div>
<div class="group-description label grid_3 alpha">Description:</div>
<div class="group-description value grid_9 omega"><?php echo $group['Group']['description']; ?></div>
<div class="group-created label grid_3 alpha">Created:</div>
<div class="group-created value grid_9 omega"><?php echo $group['Group']['created']; ?></div>
<?php
	echo $form->create('Group', array('action'=>'delete'));
	echo $form->input('Group.id', array('type'=>'hidden', 'value'=>$group['Group']['id']));
	echo $form->input('Group.delete', array('type'=>'hidden', 'value'=>1));
	echo $form->end("Yes");
	echo $html->link("Cancel", array('controller' => 'groups', 'action' => 'index'));
?>
</div>