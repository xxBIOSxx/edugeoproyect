<?php


defined('_JEXEC') or die('Restricted Access');
?>
<?php if (PuntosHelper::getSettings('footer', 1) == 1) : ?>
	<div class="Puntos-footer">
		<div class="Puntos-footer-box">
			<?php echo JText::_('COM_PUNTOS_POWERED_BY'); ?> <a href="http://www.edugeo.com"
			                                                      title="plugins y modulos edugeo">edugeo.com</a>
		</div>
	</div>
<?php endif; ?>