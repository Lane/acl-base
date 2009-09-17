<?php
class UsersController extends AppController
{
	var $name = 'Users';
	var $components = array('Cookie');
	
	function beforeFilter() 
	{
        $this->Auth->allow('register');
        $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'passwd'
        );
	}
	
	function admin_add()
	{
		if(!empty($this->data)) // form has been submitted
		{
			if($this->User->save($this->data))
			{
				// save successful: set message and redirect
				$this->Session->setFlash('Account Created');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				// save failed: clear passwords
				$this->data['User']['password'] = null;
				$this->data['User']['confirm_password'] = null;
			}
		}
		// form has not been submitted
	}
	
	function admin_edit($id=null)
	{
		if(!empty($this->data)) // form has been submitted
		{
			if($this->User->save($this->data))
			{
				// save successful: set message and redirect
				$this->Session->setFlash('Account Edited');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				// save failed: clear passwords
				$this->data['User']['password'] = null;
				$this->data['User']['confirm_password'] = null;
			}
		}
		$user = $this->User->find('first', array('conditions' => array('User.id' => $id)));
		if($user == null || $id == null)
		{
			$this->Session->setFlash("Invalid user");
		}
		$this->set(compact('user'));
	}
	
	function admin_delete($id = null)
	{
		if(!empty($this->data))
		{
			if($this->User->remove($this->data['User']['id']))
			{
				// user deleted, do something
			}
		}
		$user = $this->User->find('first', array('conditions' => array('User.id' => $id)));
		$this->set(compact('user'));
		if($user == null || $id == null)
		{
			$this->Session->setFlash("Invalid user");
		}
	}
	
	function admin_view($id=null)
	{
		$user = $this->User->find('first', array('conditions' => array('User.id' => $id)));
		if($user == null || $id == null)
		{
			$this->Session->setFlash("Invalid user");
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
	
	function login() 
	{
		if ($this->Auth->user())
		{
			if (!empty($this->data) && $this->data['User']['remember_me'])
			{
					$cookie = array();
					$cookie['username'] = $this->data['User']['username'];
					$cookie['passwd'] = $this->data['User']['passwd'];
					$this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
					unset($this->data['User']['remember_me']);
			}
			$this->redirect($this->Auth->redirect());
		}
		if (empty($this->data)) 
		{
			$cookie = $this->Cookie->read('Auth.User');
			if (!is_null($cookie)) 
			{
				if ($this->Auth->login($cookie)) 
				{
					//  Clear auth message, just in case we use it.
					$this->Session->del('Message.auth');
					$this->redirect($this->Auth->redirect());
				} 
				else 
				{ // Delete invalid Cookie
					$this->Cookie->del('Auth.User');
				}
			}
		}
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
				$this->Session->setFlash("Account Created");
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				// save failed: clear passwords
				$this->data['User']['password'] = null;
				$this->data['User']['confirm_password'] = null;
			}
		}
		// form has not been submitted
	}
}
?>