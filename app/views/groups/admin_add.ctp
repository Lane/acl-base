<div class="add-form">
<h2><?php echo $friendlyTitle; ?></h2>
<p class="page-description">Fill out the form below to add a group</p>
<?php
	$inputs = array("fieldset" => false, "legend"=>false);
	$blacklist = array("fieldset", "created", "modified");
	echo $form->create("Group");
	echo $form->inputs($inputs, $blacklist);
	echo $form->end("Save");
?>
<h2>Permissions</h2>
<div class="permissions-heading">
	<span class="permissions-heading-module">Module</span>
	<span class="permissions-heading-create">Create</span>
	<span class="permissions-heading-read">Read</span>
	<span class="permissions-heading-update">Update</span>
	<span class="permissions-heading-delete">Delete</span>
	<span class="permissions-heading-actions">&nbsp;</span>
</div>
<div class="permissions-item-form">
	<?php echo $form->create('Group', array('action' => 'permissions')); ?>
	<?php echo $form->input('Group.id', array('type' => 'hidden', 'value' => $this->data['Group']['id'])); ?>
	<span class="permissions-item-module">
	<?php echo $form->input('ArosAco.aco_id', 
		array(
			'label' => false, 
			'div'=>false, 
			'type'=>'select', 
			'options' => $acos
		)
	); ?>
	<?php echo $form->input('ArosAco.aro_id', array('type' => 'hidden', 'value' => $aro['Aro']['id'])); ?>
	</span>
	<span class="permissions-item-create">
	<?php echo $form->input('ArosAco._create', 
		array(
			'label' => false, 
			'div'=>false, 
			'type'=>'checkbox'
		)
	); ?>
	</span>
	<span class="permissions-item-read">
	<?php echo $form->input('ArosAco._read', 
		array(
			'label' => false, 
			'div'=>false, 
			'type'=>'checkbox'
		)
	); ?>
	</span>
	<span class="permissions-item-update">
	<?php echo $form->input('ArosAco._update', 
		array(
			'label' => false, 
			'div'=>false, 
			'type'=>'checkbox'
		)
	); ?>
	</span>
	<span class="permissions-item-delete">
	<?php echo $form->input('ArosAco._delete', 
		array(
			'label' => false, 
			'div'=>false, 
			'type'=>'checkbox'
		)
	); ?>
	</span>
	<span class="permissions-item-actions">
		<?php echo $form->submit('Add', array('div'=>false)); ?>
	</span>
	<?php echo $form->end(); ?>
</div>
<?php foreach($permissions as $p): ?>
<div class="permissions-item">
	<span class="permissions-item-module"><?php echo $p['Aco']['alias']; ?></span>
	<span class="permissions-item-create"><?php echo $html->image("permission_".$p['ArosAco']['_create'].".png"); ?></span>
	<span class="permissions-item-read"><?php echo $html->image("permission_".$p['ArosAco']['_read'].".png"); ?></span>
	<span class="permissions-item-update"><?php echo $html->image("permission_".$p['ArosAco']['_update'].".png");  ?></span>
	<span class="permissions-item-delete"><?php echo $html->image("permission_".$p['ArosAco']['_delete'].".png");  ?></span>
	<span class="permissions-item-actions">
		<?php echo $form->create('ArosAco', array('action' => 'delete')); ?>
			<?php echo $form->input('ArosAco.id', array('type' => 'hidden', 'value' => $p['ArosAco']['id'])); ?>
			<?php echo $form->submit("trash.png", array('div'=>false));  ?>
		<?php echo $form->end(); ?>
	</span>
</div>
<?php endforeach; ?>

</div>