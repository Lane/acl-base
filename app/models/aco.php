<?php
class Aco extends AppModel
{
	var $name = 'Aco';
	
	var $hasAndBelongsToMany = array(
		'Aro' => array(
			'className' => 'Aro',
			'joinTable' => 'aros_acos',
			'foreignKey' => 'acos_id',
			'associationForeignKey' => 'aros_id'
		)
	);
}
?>