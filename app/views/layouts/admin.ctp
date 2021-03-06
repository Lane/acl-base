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
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php __('ACL Base'); ?> :: 
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $html->meta('icon');
		echo $html->css('reset');
		echo $html->css('960');
		echo $html->css('text');
		echo $html->css('admin');

		echo $scripts_for_layout;
	?>
</head>
<body>
<div id="page">
	<div id="header-wrapper">
		<div id="header-inner" class="container_16">
			<div id="logo" class="grid_12">
				<h1>Kos Oilfield Transportation</h1>
			</div>
			<div id="userbox" class="grid_4">
				
			</div>
		
			<div class="clear"></div>
			<ul id="navigation" class="grid_16">
						<?php foreach($menu as $item): ?>
							<?php $class=$item['label']."-link"; ?>
							<?php if($item['controller'] == strtolower($controller)): ?>
								<?php $class.=" active"; ?>
							<?php endif; ?>
							<li class="<?php echo $class; ?>">
								<?php echo $html->link($item['label'], array('controller'=>$item['controller'], 'action'=>$item['action'])); ?>
							</li>
						<?php endforeach; ?>
			</ul>
		</div>
		<div class="clear"></div>
	</div>
	<div id="content-wrapper">
		<div id="content" class="container_16">
			<div id="main-content" class="grid_12">
				<?php $session->flash(); ?>
				<?php $session->flash("auth"); ?>
				<?php echo $content_for_layout; ?>
			</div>
			<div id="sidebar" class="grid_4">
				<?php if(isset($subnav) && !empty($subnav)): ?>
				<h2>Actions</h2>
				<ul>
					<?php foreach($subnav as $subitem): ?>
						<li>
						<?php echo $html->link($subitem['label'], array('controller'=>$subitem['controller'], 'action'=>$subitem['action'])); ?>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div id="footer-wrapper">
		<div id="footer" class="container_16">
		
		</div>
	</div>
</div>
</body>
</html>