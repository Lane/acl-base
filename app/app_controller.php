<?php
class AppController extends Controller 
{
	// Components to use application wide
    var $components = array('Acl', 'Auth', 'Cookie');
	
	// Helpers
	var $helpers = array('Html', 'Form', 'Gravatar');
	
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
		$cookie = null;
		$this->Auth->allow('display');
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
		$this->Auth->loginRedirect = array('controller' => 'pages', 'action' => 'display', 'home');
		$this->Auth->authorize = 'crud';
		$this->Auth->loginError = 'Sorry, login failed. Either your username or password are incorrect.';
		$this->Auth->authError = 'The page you tried to access is restricted. You have been redirected to the page below.';
        $this->Auth->allow('*'); // TEMPORARY
        $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'passwd'
        );
		
		// check if user is logged in, or if they have a valid cookie
		if(!$this->__setLoggedUserValues() && ($cookie = $this->Cookie->read($this->cookieName )))
		{
			$this->Auth->login($cookie);
			$this->__setLoggedUserValues();
		}
	}
	
	function beforeRender()
	{		
		$controllerName = Inflector::camelize($this->params['controller']);
		$this->set('controller', $controllerName);
		$actionName = $this->params['action'];
		$this->set('action', $actionName);

		$admin = Configure::read('Routing.admin');
		if (isset($this->params[$admin]) && $this->params[$admin]) 
		{
			$this->layout = 'admin';
			$this->set('menu', $this->__buildMenu($this->adminItems));
        }
		else
		{
			$this->set('menu', $this->__buildMenu($this->menuItems));
		}
		$this->set('subnav', $this->__buildMenu($this->subnavItems));
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
				$this->Cookie->del('User');
			}
			$this->set('User', $user[$this->Auth->userModel]);
			$this->loggedUser = $user[$this->Auth->userModel][$this->Auth->fields['username']];
			return true;
		}
		return false;
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
}
?>