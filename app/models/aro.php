<?php
class Aro extends AppModel
{
	var $name = 'Aro';
	
	var $hasAndBelongsToMany = array(
		'Aco' => array(
			'className' => 'Aco',
			'joinTable' => 'aros_acos',
			'foreignKey' => 'aro_id',
			'associationForeignKey' => 'aco_id'
		)
	);
}
?>