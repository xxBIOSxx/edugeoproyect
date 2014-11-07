<?php


defined('_JEXEC') or die('Restricted access');
$stats = modPuntosStatsHelper::getStats();
$state = array(
	'all' => '',
	'published' => 'P',
	'unpublished' => 'U'
);

$tiles = modPuntosStatsHelper::tilesStats();
?>

<table>
	<tbody>
		<?php foreach($stats as $key => $value) : ?>

			<tr>
				<td>
					<?php echo JText::_('MOD_PUNTOS_STATS_'.strtoupper($key)); ?>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_puntos&view=puntos&filter_state='.$state[$key]); ?>">
						<?php echo $value; ?>
					</a>
				</td>
			</tr>
		<?php endforeach; ?>

        <?php if($tiles['files']) : ?>
        <tr>
                <td><?php echo JText::_('MOD_PUNTOS_STATS_TILES'); ?></td>
                <td><?php echo JText::sprintf('COM_PUNTOS_NUMBER_OF_TILES_TAKE_X_SPACE', $tiles['files'], modPuntosStatsHelper::formatBytes($tiles['size'])); ?></td>
                <td><a href="<?php echo JRoute::_('index.php?option=com_puntos&task=tiles.delete'); ?>"><?php echo JText::_('JACTION_DELETE'); ?></a></a></td>
            </tr>
         <?php else: ?>
            <tr>
                <td><?php echo JText::_('MOD_PUNTOS_STATS_TILES'); ?></td>
                <td>0</td>
            </tr>
        <?php endif; ?>
	</tbody>
</table>

