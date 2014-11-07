<?php


defined('_JEXEC') or die('Restricted access');
?>

<div id="map-overlay">
	<div id='marker_container'>
		<h2><?php echo $this->punto->name ?></h2>

		<div id='marker_adress'>
			<?php require_once JPATH_COMPONENT . '/views/json/tmpl/address.php'; ?>
		</div>
		<div id='marker_description'>
			<?php if ($this->punto->picture) : ?>
				<a href='<?php echo $this->punto->link; ?>' title='<?php echo $this->punto->name; ?>'>
					<img src='<?php echo $this->punto->picture_thumb; ?>' align='right'
					     alt='<?php echo $this->punto->name ?>'/>
				</a>
			<?php endif; ?>
			<?php echo $this->punto->description_small; ?>
			<div class="clear-both"></div>
			<p>
				<?php if ($this->settings->get('show_author', 1)) : ?>
					<?php echo JTEXT::_('COM_PUNTOS_POSTED_BY'); ?>
					<strong>
						<?php if ($this->profile) : ?>
						<a href="<?php echo $this->profile; ?>">
						<?php endif; ?>

							 <?php echo $this->punto->created_by_alias ? $this->punto->created_by_alias : $this->punto->user_name; ?>

						<?php if ($this->profile) : ?>
						</a>
						<?php endif; ?>
					</strong>
				<?php endif; ?>

				<?php if ($this->settings->get('show_date')) : ?>
					 <?php echo JText::_('COM_PUNTOS_ON'); ?> <?php echo $this->punto->postdate; ?>
				<?php endif; ?>
			</p>

		</div>
	</div>
</div>