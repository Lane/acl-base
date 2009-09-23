<?php
/* SVN FILE: $Id$ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.pages
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
if (Configure::read() == 0):
	$this->cakeError('error404');
endif;
?>
<h2>Release Notes for Admin Base</h2>
<p class="emphasis">
This page is the main page for the ACL Base.  Anyone can view this page whether they are logged in or not.
If you have not yet logged in you can login using the form to the right and the default username and password.
Once you have logged in you will be presented with a link to the Administration portion of the website.
</p>
<h3>What is the point of this?</h3>
<p>The ACL Base has been created to help in the creation of more advanced applications that require a form of
 authentication and authorization.  The ACL Base is meant to be a building block for your application.  It allows
 the creation of users and groups.  The ACL Base also gives the ability to assign permissions to Request Objects 
 for different Control Objects.</p>