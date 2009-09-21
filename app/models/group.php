<?php
class Group extends AppModel
{
	var $name = 'Group';
	
	var $validate = array(
		'name' => array(
			'rule' => array('minlength', 3),
			'message' => "The users first name must be at least 3 characters."
		)
	);
	
	var $actsAs = array('Acl'=>'requester');
	
	var $hasMany = 'User';
	
	/**
	* Allows the AclBehavior to determine parental ownership of
	* currently active record.
	*
	* @access public
	* @returns array data array to be used by AclBehavior for node lookup
	*/
	function parentNode()
	{
		return null;
	}
 
	/**
	* processes completed after saving record
	*
	*/
	function afterSave($created = null)
	{
		if( $created )
		{
			$this->id = $this->getLastInsertId();
			// first create alias for the newly created Aro
			$this->__createAroAlias();
		}
		else
		{
			$this->__updateAclGroup();
		}
	}
	
	/**
	* Creates an alias for the newly created Aro record -- AclBehavior
	* does not create an alias automatically.
	*
	* @access private
	* @returns boolean TRUE if alias is successfully added to the recently
	* created Aro node
	*/
	function __createAroAlias()
	{
		$aroId = $this->Aro->getLastInsertId();
		$this->Aro->create();
		$this->Aro->id = $aroId;
		if($this->Aro->saveField('alias', $this->data['Group']['name']))
			return true;
		else
			return false;
	}
	
	/**
	* If parent has changed, need to make sure new parent is set in Aro record
	*
	* @access private
	* @returns NULL
	*/
	function __updateAclGroup()	
	{
		// update name in ARO?
	}
}
?>