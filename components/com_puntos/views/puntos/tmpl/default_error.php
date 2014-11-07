<?php


defined('_JEXEC') or die('Restricted access'); ?>

<div class="puntos">
	<?php if (!count($this->cats)) : ?>
		<?php echo JText::_('COM_PUNTOS_NO_CATS_EXPLAINED'); ?>
	<?php endif; ?>
</div>