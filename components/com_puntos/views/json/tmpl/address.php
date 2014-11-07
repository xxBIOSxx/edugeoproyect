<?php


defined('_JEXEC') or die('Restricted access');
?>

<?php if (PuntosHelper::getSettings('show_address', 1)) : ?>
	<?php if (PuntosHelper::getSettings('user_interface', 0) == 0) : ?>
		<?php echo $this->Punto->street ?>
		<?php echo $this->Punto->plz ? ', ' . $this->punto->plz : ''; ?>
		<?php echo $this->Punto->town ? ' ' . $this->punto->town : ''; ?>
	<?php else: ?>
		<?php echo $this->Punto->street ?>
		<?php echo $this->Punto->town ? ', ' . $this->punto->town : ''; ?>
		<?php echo $this->Punto->plz ? ', ' . $this->punto->plz : ''; ?>
	<?php endif; ?>

	<?php if (PuntosHelper::getSettings('show_address_country', 0)) : ?>
		<?php echo ', '.$this->Punto->country; ?>
	<?php endif; ?>
<?php endif; ?>