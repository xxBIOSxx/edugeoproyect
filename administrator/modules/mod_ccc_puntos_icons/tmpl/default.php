<?php

defined('_JEXEC') or die();
$lang = JFactory::getLanguage();
$lang->load('com_puntos.sys',JPATH_ADMINISTRATOR);
$path = JURI::root() . '/media/com_puntos/backend/images/';
?>
<div id="cpanel" style="margin-top: 10px;">

	<div class="icon-wrapper">
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option=com_puntos&view=puntos'); ?>">
				<img src="<?php echo $path; ?>puntos-48px.png" alt="" />
				<span><?php echo JText::_('COM_PUNTOS_LOCATIONS'); ?></span>
			</a>
		</div>
		<?php if(puntoS_PRO): ?>
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_puntos&view=kml'); ?>">
					<img src="<?php echo $path; ?>kmls-48px.png" alt="" />
					<span><?php echo JText::_('COM_PUNTOS_KML'); ?></span>
				</a>
			</div>
		<?php endif; ?>
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option=com_puntos&view=categories'); ?>">
				<img src="<?php echo $path; ?>categories-48px.png" alt="" />
				<span><?php echo JText::_('COM_PUNTOS_CATEGORIES'); ?></span>
			</a>
		</div>
		<?php echo LiveUpdate::getIcon(); ?>
	</div>

</div>