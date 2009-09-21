<?php
class AppController extends Controller 
{
	// Components to use application wide
    var $components = array('Acl', 'Auth', 'Cookie');
	
	// Determines if a user can use the remember me feature of the Users/login function
	var $allowCookie = TRUE;
	 
	// Determines length of time that the cookie will be valid.
	var $cookieTerm = '+3 weeks';
	 
	// Name to use for cookie holding user values
	var $cookieName = 'User';
	
	function beforeFilter()
	{
		$cookie = null;
		$this->Auth->allow('display');
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
		$this->Auth->loginRedirect = array('controller' => 'pages', 'action' => 'display', 'home');
		$this->Auth->authorize = 'controller';
		$this->Auth->loginError = 'Sorry, login failed. Either your username or password are incorrect.';
		$this->Auth->authError = 'The page you tried to access is restricted. You have been redirected to the page below.';
		// check if user is logged in, or if they have a valid cookie
		if(!$this->__setLoggedUserValues() && ($cookie = $this->Cookie->read($this->cookieName )))
		{
			$this->Auth->login($cookie);
			$this->__setLoggedUserValues();
		}
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
			return true;
		}
		return false;
	}
	
	function isAuthorized() 
	{
		return true;
	}
}
?>