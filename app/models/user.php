<?php
class User extends AppModel
{
	var $name = "User";
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
	var $belongsTo = 'Group';
	
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
	
    function identicalFieldValues( $field=array(), $compare_field=null ) 
    {
        foreach( $field as $value )
		{
            $v1 = $value;
            $v2 = $this->data[$this->name][ $compare_field ];                 
            return $v1==$v2; 
        }
    }
}
?>