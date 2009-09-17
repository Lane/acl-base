<?php
class AppController extends Controller 
{
    var $components = array('Auth');
	
	function beforeFilter()
	{
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
		$this->Auth->loginRedirect = array('controller' => 'pages', 'action' => 'display', 'home');
		$this->Auth->allow('display');
		$this->Auth->authorize = 'controller';
	}
	
	function isAuthorized() 
	{
		return true;
	}
}
?>