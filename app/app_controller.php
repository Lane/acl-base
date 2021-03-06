<?php
class AppController extends Controller 
{
	// Components to use application wide
    var $components = array('Acl', 'Auth', 'Cookie');
	var $uses = array('User');
	
	// Helpers
	var $helpers = array('Html', 'Form', 'Gravatar', 'Time');
	
	// Determines if a user can use the remember me feature of the Users/login function
	var $allowCookie = TRUE;
	 
	// Determines length of time that the cookie will be valid.
	var $cookieTerm = '+3 weeks';
	 
	// Name to use for cookie holding user values
	var $cookieName = 'User';
	
	var $loggedUser = null;
	
	var $menuItems = array(
		array(
			'restricted' => false,
			'label' => 'Home',
			'action' => 'index',
			'controller' => 'Pages',
			'crud' => 'read'
		),
		array(
			'restricted' => false,
			'label' => 'Posts',
			'action' => 'index',
			'controller' => 'Posts',
			'crud' => 'read'
		)
	);
	
	var $adminItems = array(
		array(
			'restricted' => false,
			'label' => 'Home',
			'action' => 'index',
			'controller' => 'dashboard',
			'crud' => 'read'
		),
		array(
			'restricted' => false,
			'label' => 'Users',
			'action' => 'index',
			'controller' => 'Users',
			'crud' => 'read'
		),
		array(
			'restricted' => false,
			'label' => 'Groups',
			'action' => 'index',
			'controller' => 'Groups',
			'crud' => 'read'
		)
	);
	
	var $subnavItems = array();
	
	function beforeFilter()
	{
		$this->Auth->allow('display');
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
		$this->Auth->loginRedirect = array('controller' => 'pages', 'action' => 'display', 'home');
		$this->Auth->authorize = 'crud';
		$this->Auth->loginError = 'Sorry, login failed. Either your username or password are incorrect.';
		$this->Auth->authError = 'The page you tried to access is restricted. You have been redirected to the page below.';
        $this->Auth->allow('login', 'logout'); // TEMPORARY
        $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'passwd'
        );
		
		$this->__setLoggedUserValues();
	}
	
	function beforeRender()
	{		
		$controllerName = Inflector::camelize($this->params['controller']);
		$this->set('controller', $controllerName);
		$actionName = $this->params['action'];
		$this->set('action', $actionName);

		$admin = Configure::read('Routing.admin');
		if (isset($this->params[$admin]) && $this->params[$admin] && $this->action != "admin_login") 
		{
			$this->layout = 'admin';
			$this->set('menu', $this->__buildMenu($this->adminItems));
        }
		else
		{
			$this->set('menu', $this->__buildMenu($this->menuItems));
		}
		$this->set('subnav', $this->__buildMenu($this->subnavItems));
		$this->set('friendlyTitle', $this->__getFriendlyTitle());
	}
	
	/**
	 * Sets a value for current logged user that is easily accessed by rest of application.
	 * @returns boolean TRUE if there is a logged user FALSE if no user is logged in.
	*/
	function __setLoggedUserValues()
	{
		$user = null;
		if($user = $this->Auth->user())
		{
			if($this->Auth->user('enabled') == 0)
			{
				$this->Session->setFlash('Your account has been disabled.','default', array('class' => 'error-message'));
				$this->Auth->logout();
				return false;
			}
			$this->User->id = $this->Auth->user('id');
			$this->User->saveField('login_count', $this->Auth->user('login_count')+1);
			$this->set('User', $user[$this->Auth->userModel]);
			$this->loggedUser = $user[$this->Auth->userModel][$this->Auth->fields['username']];
			return true;
		}
		return false;
	}
	
	function __getFriendlyTitle()
	{
			$isEdit = (
				strpos($this->action, 'update') !== false ||
				strpos($this->action, 'edit') !== false
			);
			$isAdd = (
				strpos($this->action, 'add') !== false ||
				strpos($this->action, 'new') !== false
			);
			$isRemove = (
				strpos($this->action, 'delete') !== false ||
				strpos($this->action, 'remove') !== false
			);
			if ($isEdit) 
			{
				$actionName = __('Edit', true);
			}
			else if($isAdd)
			{
				$actionName = __('New', true);
			}
			else if($isRemove)
			{
				$actionName = __('Remove', true);
			}
			else
			{
				$actionName = null;
			}
			$modelName = Inflector::humanize(Inflector::underscore($this->name));
			$modelName = Inflector::singularize($modelName);
			return $friendlyTitle = $actionName .' '. __($modelName, true);
	}
	
	/**
	* Builds a menu adding restricted links, if user is logged in.
	* @access private
	* @returns null
	*/
	function __buildMenu($items)
	{
		$menu = array();
		foreach( $items as $menuLink )
		{
			if( $menuLink['restricted'] )
			{
				if( !$this->loggedUser || 
					!$this->Acl->check($this->loggedUser, $menuLink['controller'], $menuLink['crud'])
				)
					continue;
			} 
			$menu[] = array(
				'label' => __($menuLink['label'], true), 
				'controller' => strtolower($menuLink['controller']), 
				'action' => $menuLink['action']
			);
		}
		return $menu;
	}
	
	function __getAclArray($aro, $aco)
	{
		$aclArray['create'] = 
			$this->Acl->check($aro, $aco, 'create');
		$aclArray['delete'] = 
			$this->Acl->check($aro, $aco, 'delete');
		$aclArray['read'] = 
			$this->Acl->check($aro, $aco, 'read');
		$aclArray['update'] = 
			$this->Acl->check($aro, $aco, 'update');
			
		return $aclArray;
	}
}
?>