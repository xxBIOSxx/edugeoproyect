<?php


defined('_JEXEC') or die('Restricted access');
?>
<table class="contentpaneopen">
	<thead>
	<th>
		#
	</th>
	<th>
		<?php echo JText::_('COM_PUNTOS_TITLE'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_PUNTOS_DATE'); ?>
	</th>
	</thead>
	<tbody>
	<?php foreach ($this->puntos as $key => $punto) : ?>
		<tr>
			<td><?php echo $key + 1; ?></td>
			<td><a href="<?php echo $punto->link ?>"><?php echo $punto->name; ?></a>
				<?php if ($this->user->authorise('core.edit', 'com_puntos') || $this->user->authorise('core.edit.own', 'com_puntos')) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_puntos&task=form.edit&id=' . $punto->id); ?>">
						<img src="<?php echo JURI::root() ?>/media/com_puntos/images/utils/edit.png" alt="edit"/></a>
				<?php endif; ?>
			</td>
			<td><?php echo $punto->created; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>