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
		echo $html->css('site');

		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="page">
		<div id="header-full">
			<div id="header" class="container_16">
				<div class="grid_4">
				<h1>
					<?php echo $html->link(__('ACL Base', true), array('action' => 'index')); ?>
				</h1>
				<span class="tagline">Are belong to us</span>
				</div>
				<div class="grid_12">
					&nbsp;
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div id="content-full">
			<div id="content" class="container_16">
				<div class="grid_4">
					<ul id="navigation">
						<?php foreach($menu as $item): ?>
							<?php $class=$item['label']."-link"; ?>
							<?php if($item['controller'] == strtolower($controller)): ?>
								<?php $class.=" active"; ?>
							<?php endif; ?>
							<li class="<?php echo $class; ?>">
							<?php echo $html->link($item['label'], array('controller'=>$item['controller'], 'action'=>$item['action'])); ?>
							<?php if($item['controller'] == strtolower($controller)): ?>
								<?php foreach($subnav as $subitem): ?>
									<ul>
										<li class="<?php echo $class; ?>">
										<?php echo $html->link($subitem['label'], array('controller'=>$subitem['controller'], 'action'=>$subitem['action'])); ?>
										</li>
									</ul>
								<?php endforeach; ?>
							<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="grid_12">
					<?php $session->flash(); ?>
					<?php $session->flash("auth"); ?>
					<?php echo $content_for_layout; ?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		
		<div id="footer-full">
			<div id="footer" class="container_16">
				<?php echo $html->link(
						$html->image('cake.power.gif', array('alt'=> __("CakePHP: the rapid development php framework", true), 'border'=>"0")),
						'http://www.cakephp.org/',
						array('target'=>'_blank'), null, false
					);
				?>
			</div>
			<div class="clear"></div>
		</div>
		<?php echo $cakeDebug; ?>
	</div>
</body>
</html>