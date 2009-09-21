<?php
class User extends AppModel
{
	var $name = 'User';
	var $belongsTo = 'Group';
	
	var $actsAs = array('Acl'=>'requester');
	
    var $validate = array(
		'username' => array(
			'required' => array(
				'rule' => 'notEmpty', 
				'message' => 'Please enter your login name'
			),
			'pattern' => array(
				'rule' => array('custom', '/[a-zA-Z0-9\_\-]{4,30}$/i'),
				'message' => 'Must be 4 characters or longer with no spaces.'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'This username is already in use, please try another.'
			)
		),
		'email_address' => array(
			'rule' => 'email', 
			'message' => 'Please enter your email address'
		),
        'password' => array(
			'identicalFieldValues' => array(
				'rule' => array('identicalFieldValues', 'confirm_password' ),
				'message' => 'Please re-enter your password twice so that the values match'
			),
			'required' => array(
				'rule' => array('custom','/(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/i'),
				'message'=> 'Must be 6 characters or longer'
			)
		)
	); 
	
	function beforeSave()  
	{  
		if (isset($this->data['User']['password']))  
		{  
			$this->data['User']['passwd'] = Security::hash($this->data['User']['password'], null, true);  
			unset($this->data['User']['password']);  
		}

		if (isset($this->data['User']['confirm_password']))  
		{  
			unset($this->data['User']['confirm_password']);  
		}
		return true;  
	}
	
	function afterSave($created = null)
	{
		if($created)
		{
			$this->id = $this->getLastInsertId();
			// first create alias for the newly created Aro
			// ACL Behavior does NOT manage alias values
			$this->__createAroAlias();
		}
		else
		{
			if(isset($this->data['User']['group_id']) && isset( $this->data['User']['old_group_id']))
				$this->__updateAclGroup();
		}
	}

	/**
	* Allows the AclBehavior to determine parental ownership of
	* currently active record.
	*
	* @access public
	* @returns array data array to be used by AclBehavior for node lookup
	*/
	function parentNode()
	{
		if (!$this->id)
			return null;
			
		$data = $this->read();
		
		if (!$data['User']['group_id'])
			return null;
			
		return array('model' => 'Group', 'foreign_key' => $data['User']['group_id']);
	}

	/**
	* Compares two fields for validation
	*
	* @returns returns TRUE if the two fields are equal
	* @access public
	*/	
    function identicalFieldValues( $field=array(), $compare_field=null ) 
    {
        foreach( $field as $value )
		{
            $v1 = $value;
            $v2 = $this->data[$this->name][ $compare_field ];                 
            return $v1==$v2; 
        }
    }
	
	/**
	* When the group_id has changed, then need to also change the parent_id field in the
	* matching Aro row.
	*
	* @access private
	*/
	function __updateAclGroup()
	{
		if($this->data['User']['group_id'] !== $this->data['User']['old_group_id'])
		{
			// what is the id of the aro row that has $this->data['User']['group_id'] as it's foreign_key?
			$groupInfo = $this->Aro->find(array('foreign_key'=>$this->data['User']['group_id'], 'model'=>'Group') );
			$userAro = $this->Aro->find(array('foreign_key'=>$this->data['User']['id'], 'model'=>'User') );
			$updatedAro = array(
				'Aro' => array(
					'id' => $userAro['Aro']['id'],
					'parent_id' => $groupInfo['Aro']['id']
				)
			);
			$this->Aro->save($updatedAro);
		}
	}
	
	/**
	* Creates an alias value for a newly created user.
	*
	* @returns boolean TRUE if alias value successfully changed.
	*/
	function __createAroAlias()
	{
		$aroId = $this->Aro->getLastInsertId();
		$this->Aro->create();
		$this->Aro->id = $aroId;
		if($this->Aro->saveField('alias', $this->data['User']['username']))
			return true;
		return false;
	}
}
?>