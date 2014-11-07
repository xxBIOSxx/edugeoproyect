<?php


defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
jimport('joomla.filter.output');
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table>
		<tr>
			<td align="left" width="100%"><?php echo JText::_('COM_PUNTOS_FILTER'); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->filter['search']; ?>"
				       class="text_area" onchange="document.adminForm.submit();"/>
				<button onclick="this.form.submit();"><?php echo JText::_('COM_PUNTOS_GO'); ?></button>
				<button
					onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_PUNTOS_RESET'); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php
				echo $this->filter['state'];
				?>
			</td>
		</tr>
	</table>

	<div id="editcell">
		<table class="adminlist table">
			<thead>
			<tr>
				<th width="5"><?php echo JText::_('JGRID_HEADING_ROW_NUMBER'); ?></th>
				<th width="5">
					<input type="checkbox" name="toggle" value=""
					       onclick="Joomla.checkAll(this)"/>
				</th>
				<th class="title"><?php echo JHTML::_('grid.sort', 'COM_PUNTOS_CATEGORY_NAME', 'cc.cat_name', $this->filter['order_Dir'], $this->filter['order']); ?></th>
				<th width="10%"><?php echo JText::_('COM_PUNTOS_ICON'); ?></th>
				<th width="10%"><?php echo JText::_('COM_PUNTOS_MARKERS'); ?></th>
				<th width="30%"><?php echo JText::_('COM_PUNTOS_DESCRIPTION'); ?></th>
				<th width="5%" nowrap="nowrap"><?php echo JText::_('COM_PUNTOS_PUBLISHED'); ?></th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
			</tfoot>
			<tbody>
			<?php
			$i = 0;
			foreach ($this->list as $l)
			{
				$hs_catpath = (JURI::root() . "media/com_puntos/images/categories/");
				$checked = JHTML::_('grid.id', $i, $l->id);
				$published = JHTML::_('jgrid.published', $l->published, $i, 'categories.');

				$link = JRoute::_('index.php?option=com_puntos&task=category.edit&id=' . $l->id);
				?>
				<tr class="<?php echo "row" . $i % 2; ?>">
					<td><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>"><?php echo $l->cat_name; ?></a>
					</td>
					<td align="center">
						<img src="<?php echo $hs_catpath . $l->cat_icon; ?>" title="<?php echo $l->cat_name; ?>"
						     alt="<?php echo $l->cat_name; ?>"/>
					</td>
					<td align="center">
						<?php echo $l->count; ?>
					</td>
					<td>
						<?php echo $l->cat_description; ?>
					</td>
					<td align="center">
						<?php echo $published; ?>
					</td>
				</tr>
				<?php
				$i++;
			}
			?>
			</tbody>
		</table>
	</div>
	<input type="hidden" name="option" value="com_puntos"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="categories"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->filter['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter['order_Dir']; ?>"/>
	<?php echo JHTML::_('form.token'); ?>
</form>