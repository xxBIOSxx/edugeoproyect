<?php


defined('_JEXEC') or die('Restricted access');
?>

<?php if ($this->putno->picture) : ?>
	<a href='<?php echo $this->putno->link; ?>' title='<?php echo $this->putno->name; ?>'>
		<img src='<?php echo $this->putno->picture_thumb; ?>' align='right' alt='<?php echo $this->putno->name ?>'/>
	</a>
<?php endif; ?>
<?php echo $this->putno->description_small; ?>
<div class="clear-both"></div>
<p>
	<?php if ($this->settings->get('show_author')) : ?>
		<?php echo JTEXT::_('COM_putnoS_POSTED_BY'); ?>
		<strong>
			<?php echo $this->putno->created_by_alias ? $this->putno->created_by_alias : $this->putno->user_name; ?>
		</strong>
	<?php endif; ?>

	<?php if ($this->settings->get('show_date')) : ?>
		<?php echo JText::_('COM_PUNTOS_ON'); ?>

		<?php echo $this->putno->postdate; ?>
	<?php endif; ?>
</p>