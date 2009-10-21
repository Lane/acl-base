<?php
class UsersController extends AppController
{
	var $name = 'Users';
	var $uses = array('User', 'Aro', 'Group', 'Aco', 'ArosAco');
	
	function beforeFilter() 
	{
		parent::beforeFilter(); 
		$this->subnavItems = array(
			array(
				'restricted' => false,
				'label' => 'Create New User',
				'action' => 'admin_add',
				'controller' => 'Users',
				'crud' => 'create'
			)
		);
	}
	
	function admin_index()
	{
		$users = $this->User->find('all');
		$this->set(compact('users'));
	}
	
	function admin_add()
	{
		if(!empty($this->data)) // form has been submitted
		{
			$this->data['User']['group_id'] = $this->data['User']['Group'];
			unset($this->data['User']['Group']);
			if($this->User->save($this->data))
			{
				// save successful: set message and redirect
				$this->Session->setFlash('Account Created');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				// save failed: clear passwords
				unset($this->data['User']['password']);
				unset($this->data['User']['confirm_password']);
			}
		}
		// form has not been submitted or did not validate
		// set defaults
		$this->data['User']['group_id'] = 3;
		$this->data['User']['enabled'] = 1;
		$this->set('groups', $this->Group->find('list'));
	}
	
	function admin_edit($id=null)
	{
		if(!empty($this->data)) // form has been submitted
		{
			if($this->data['User']['passwd'] == Security::hash('', null, true))
				unset($this->data['User']['passwd']);  // unset if blank
			
			$this->data['User']['group_id'] = $this->data['User']['Group'];
			unset($this->data['User']['Group']);
			if($this->User->save($this->data))
			{
				// save successful: set message and redirect
				$this->Session->setFlash('Account Edited');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				// save failed: clear passwords
				unset($this->data['User']['password']);
				unset($this->data['User']['confirm_password']);
			}
		}
		$this->data = $this->User->find('first', array('conditions' => array('User.id' => $id)));
		unset($this->data['User']['passwd']);
		if($this->data == null || $id == null)
		{
			$this->Session->setFlash('Invalid user');
			// redirect to error page
		}
		$groups = $this->Group->find('list');
		$aro = $this->Aro->find('first', array(
			'fields' => array('Aro.id'),
			'conditions'=>array('Aro.foreign_key'=>$id, 'Aro.model' => 'User')
			)
		);
		$permissions = $this->ArosAco->find('all', array(
			'conditions' => array('ArosAco.aro_id' => $aro['Aro']['id'])
			)
		);
		$acos = $this->Aco->generatetreelist(null, '{n}.Aco.id', '{n}.Aco.alias', '. . ');
		$this->set(compact('acos', 'permissions', 'aro', 'groups'));
		$this->render('admin_add');
	}
	
	function admin_delete($id = null)
	{
		if(!empty($this->data))
		{
			if($this->data['User']['delete'] == 1)
			{
				if($this->User->delete($this->data['User']['id']))
				{
					// user deleted, do something
					$this->Session->setFlash('User deleted.');
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					// problem deleting, do something else
				}
			}
		}
		$user = $this->User->find('first', array('conditions' => array('User.id' => $id)));
		$this->set(compact('user'));
		if($user == null || $id == null)
		{
			$this->Session->setFlash('Invalid user');
		}
	}
	
	function admin_view($id=null)
	{
		$user = $this->User->find('first', array('conditions' => array('User.id' => $id)));
		if($user == null || $id == null)
		{
			$this->Session->setFlash('Invalid user');
		}
		$this->set(compact('user'));
	}
	
	function edit()
	{
	
	}
	
	function view()
	{
	
	}

	function index()
	{
	
	}
	
	function admin_login() 
	{
		$this->Auth->loginRedirect = array(
			'controller' => 'dashboard', 
			'action' => 'display', 
			'index'
		);
		$this->layout = "login";
		$this->login();
	}
	
	function login() 
	{
	
	}

    function logout()
	{
		$this->Session->setFlash("You have been logged out.");
        $this->redirect($this->Auth->logout());
    }
	
	function register()
	{
		if(!empty($this->data)) // form has been submitted
		{
			// put in the default group
			$this->data['User']['group_id'] = 1;
			if($this->User->save($this->data))
			{
				// save successful: set message and redirect
				$this->Session->setFlash('Account Created');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				// save failed: clear passwords
				unset($this->data['User']['password']);
				unset($this->data['User']['confirm_password']);
			}
		}
		// form has not been submitted
	}
}
?>