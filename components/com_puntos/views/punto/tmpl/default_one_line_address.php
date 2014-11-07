<?php


defined('_JEXEC') or die('Restricted access');
?>
<?php if (puntosHelper::getSettings('show_address', 1)) : ?>
	<div class="one-line-address">
		<?php if (puntosHelper::getSettings('user_interface', 0)) : ?>
			<?php echo $this->punto->street ?><?php echo ($this->punto->town) ? ',' : ''; ?>
			<?php echo $this->punto->town ?><?php echo ($this->punto->plz) ? ',' : ''; ?>
			<?php echo $this->punto->plz ?><?php echo ($this->punto->country && $this->settings->get('show_country')) ? ',' : ''; ?>


			<?php if ($this->settings->get('show_country')) : ?>
				<?php echo $this->punto->country; ?>
			<?php endif; ?>

		<?php else: ?>
			<?php echo $this->punto->street ?><?php echo ($this->punto->plz) ? ',' : ''; ?>
			<?php echo $this->punto->plz ?>
			<?php echo $this->punto->town ?><?php echo ($this->punto->country && $this->settings->get('show_country')) ? ',' : ''; ?>

			<?php if ($this->settings->get('show_country')) : ?>
				<?php echo $this->punto->country; ?>
			<?php endif; ?>

		<?php endif; ?>
	</div>
<?php endif; ?>