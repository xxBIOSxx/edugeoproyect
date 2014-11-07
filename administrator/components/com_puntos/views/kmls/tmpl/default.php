<?php


defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
jimport('joomla.filter.output');
$listDirn	= $this->escape($this->lists['order_Dir']);
$listOrder	= $this->escape($this->lists['order']);
$document = JFactory::getDocument();
$document->addStyleDeclaration(".icon-48-kmls{background: url(../media/com_puntos/backend/images/kmls-48px.png) no-repeat;}")
?>
<form action="<?php echo JRoute::_('index.php?option=com_puntos&view=kmls');?>" method="post" name="adminForm"
      id="adminForm">
    <fieldset id="filter-bar fluid-row">
        <div class="filter-search fltlft pull-left">
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />

			<button onclick="this.form.submit();"><?php echo JText::_('COM_PUNTOS_GO'); ?></button>
            <button
                onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_PUNTOS_RESET'); ?></button>
        </div>
        <div class="filter-select fltrt pull-right">
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => 0, 'trash'=>0, 'all' => 0)), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
        </div>
    </fieldset>

    <div class="clr"></div>

    <table class="adminlist table">
        <thead>
        <tr>
            <th>
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
            </th>
			<th>
				<?php echo JText::_('COM_PUNTOS_KML_TITLE'); ?>
			</th>
            <th>
                <?php echo JText::_('COM_PUNTOS_KML_DESCRIPTION'); ?>
            </th>
            <th>
                <?php echo JHtml::_('grid.sort', 'JCATEGORY', 'cat.cat_name', $listDirn, $listOrder); ?>
            </th>
			<th>
				<?php echo JHTML::_('grid.sort', 'COM_PUNTOS_KML_ORIGINAL_FILENAME', 'kmls.original_filename', $listDirn, $listOrder); ?>
			</th>
            <th>
				<?php echo JText::_('COM_puntoS_KML_MANGLED_FILE'); ?>
            </th>
            <th>
                <?php echo JText::_('JGLOBAL_FIELD_CREATED_LABEL'); ?>
            </th>
			<th>
				<?php echo JText::_('JGLOBAL_FIELD_CREATED_BY_LABEL'); ?>
			</th>
            <th>
                <?php echo JText::_('JSTATUS'); ?>
            </th>
        </tr>
        </thead>

        <tbody>
            <?php foreach ($this->kmls as $i => $kml) : ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center" width="1%">
						<?php echo JHtml::_('grid.id', $i, $kml->puntos_kml_id); ?>
					</td>
					<td>
						<a href="<?php echo JRoute::_('index.php?option=com_puntos&task=kml.edit&puntos_kml_id='.$kml->puntos_kml_id); ?>">
							<?php echo $kml->title; ?>
						</a>
					</td>
					<td>
						<?php echo $kml->description; ?>
					</td>
					<td>
						<?php echo $kml->cat_name; ?>
					</td>
					<td>
						<?php echo $kml->original_filename; ?>
					</td>
					<td>
						<?php echo $kml->mangled_filename; ?>
					</td>
					<td>
						<?php echo $kml->created; ?>
					</td>
					<td>
						<?php echo $kml->user_name; ?>
					</td>
					<td>
						<?php echo JHtml::_('jgrid.published', $kml->state, $i, 'kmls.'); ?>
					</td>
				</tr>
            <?php endforeach; ?>
        </tbody>

        <tfoot>
        <tr>
            <td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
        </tr>
        </tfoot>
    </table>

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
    <?php echo JHTML::_('form.token'); ?>
</form>