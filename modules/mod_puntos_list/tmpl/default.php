<?php

defined('_JEXEC') or die('Restricted access');

JLoader::register('puntosHelper', JPATH_ADMINISTRATOR . '/components/com_puntos/helpers/puntos.php');
JLoader::discover('puntosHelper', JPATH_BASE . '/components/com_puntos/helpers/');
JLoader::register('puntosUtils', JPATH_BASE . '/components/com_puntos/utils.php');
?>
<ul>
	<?php foreach($list as $punto) : ?>
		<?php $punto->puntos_id = $punto->id; ?>
		<li>
			<a href="<?php echo puntosUtils::createLink($punto); ?>">
				<?php echo $punto->name; ?>
			</a>
			created by
			<?php if($punto->created_by) : ?>
				<?php $punto->user_name; ?>
			<?php else: ?>
				<?php echo $punto->created_by_alias; ?>
			<?php endif; ?>
			on
			<?php echo puntosUtils::getLocalDate($punto->created); ?>
		</li>

	<?php endforeach; ?>
</ul>