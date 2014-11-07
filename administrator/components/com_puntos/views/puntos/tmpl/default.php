<?php

defined('_JEXEC') or die('restricted access');
$user = JFactory::getUser();
JHTML::_('behavior.tooltip');
JHTML::_('behavior.framework');
//JHTML::_('behavior.modal');
JHTML::_('stylesheet', 'media/com_puntos/css/puntos.css');
JHTML::_('stylesheet', 'media/com_puntos/css/puntos-backend.css');
JHTML::_('script', 'media/com_puntos/js/fixes.js');
JHTML::_('script', 'media/com_puntos/js/helpers/helper.js');
JHTML::_('script', 'media/com_puntos/js/modules/backend/markersgeocode.js');
JHTML::_('script', 'media/com_puntos/js/lightface/LightFace.js');
JText::script('COM_puntoS_GEOCODE');
JText::script('COM_puntoS_GEOCODING_NOTICE');
JText::script('COM_puntoS_CLOSE');
?>
<script type="text/javascript">

    Joomla.submitbutton = function(button) {

        if(button == 'geocode') {
	        var markersGeocode = new compojoom.puntos.modules.markersgeocode();
	        markersGeocode.geocodeModal();

            return false;
        }
        Joomla.submitform(button);
    }
</script>
<?php if(!PUNTOS_PRO): ?>
	<?php if($this->pagination->total <= 100) : ?>
		<div class="alert alert-info">
			<?php echo JText::sprintf('COM_PUNTOS_CORE_VERSION_LIMIT_100_PUNTOS', $this->pagination->total); ?>
		</div>
	<?php else : ?>
		<div class="alert alert-info">
			<?php echo JText::sprintf('COM_PUNTOS_CORE_VERSION_NEED_MORE_THAN_100_PUNTOS', $this->pagination->total, 'https://compojoom.com/joomla-extensions/puntos'); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
<form action="index.php?option=com_puntos&view=puntos" method="post" id="adminForm" name="adminForm">
	<table>
		<tr>
			<td align="left" width="100%"><?php echo JText::_('COM_PUNTOS_FILTER'); ?>:
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />

				<button onclick="this.form.submit();"><?php echo JText::_('COM_PUNTOS_GO'); ?></button>
				<button onclick="document.getElementById('filter_search').value='';this.form.submit();"><?php echo JText::_('COM_PUNTOS_RESET'); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php
				echo $this->lists['sectionid'];
				?>
				<select name="filter_published" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
					<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => 0, 'trash'=>0, 'all' => 0)), 'value', 'text', $this->state->get('filter.published'), true);?>
				</select>
			</td>
		</tr>
	</table>

	<div id="editcell">
        <table class="adminlist table">
			<thead>
				<tr>
					<th width="5"><?php echo JText::_('COM_PUNTOS_NUM'); ?></th>
					<th width="5">
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
					</th>
					<th class="title"><?php echo JHTML::_('grid.sort', 'COM_PUNTOS_TITLE', 'a.name', $this->lists['order_Dir'], $this->lists['order']); ?></th>
					<th class="title"><?php echo JHTML::_('grid.sort', 'COM_PUNTOS_DATE', 'a.created', $this->lists['order_Dir'], $this->lists['order']); ?></th>
					<th width="15%"><?php echo JHTML::_('grid.sort', 'COM_PUNTOS_STREET', 'a.street', $this->lists['order_Dir'], $this->lists['order']); ?></th>
					<th width="5%"><?php echo JHTML::_('grid.sort', 'COM_PUNTOS_PLZ', 'a.plz', $this->lists['order_Dir'], $this->lists['order']); ?></th>
					<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_PUNTOS_TOWN', 'a.town', $this->lists['order_Dir'], $this->lists['order']); ?></th>
					<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_PUNTOS_COUNTRY', 'a.country', $this->lists['order_Dir'], $this->lists['order']); ?></th>
					<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_PUNTOS_CATEGORY', 'cat.cat_name', $this->lists['order_Dir'], $this->lists['order']); ?></th>
					<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_PUNTOS_LATITUDE', 'a.gmlat', $this->lists['order_Dir'], $this->lists['order']); ?></th>
					<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_PUNTOS_LONGITUDE', 'a.gmlng', $this->lists['order_Dir'], $this->lists['order']); ?></th>
					<th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'JPUBLISHED', 'a.published', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
			</tfoot>
			<tbody>
				<?php
				$i = 0;
				foreach ($this->list as $l) {


					$link = JRoute::_('index.php?option=com_puntos&task=punto.edit&id=' . $l->id);
                    $canEdit	= $user->authorise('core.edit', 'com_puntos' );
                    $canChange	= $user->authorise('core.edit.state',	'com_puntos');

                    $checked = JHTML::_('grid.id', $i, $l->id);
                    $published = JHTML::_('jgrid.published', $l->published, $i, 'puntos.', $canChange);
                    ?>
					<tr class="<?php echo "row" . $i % 2; ?>">
						<td><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td>
							<?php echo $checked; ?>
						</td>
						<td>
                            <?php if($canEdit) : ?>
							    <a href="<?php echo $link; ?>"><?php echo $l->name; ?></a>
                            <?php else : ?>
                                <?php echo $l->name; ?>
                            <?php endif; ?>
						</td>
						<td>
							<?php echo puntosUtils::getLocalDate($l->created); ?>
						</td>
						<td>
							<?php echo $l->street; ?>
						</td>
						<td>
							<?php echo $l->plz; ?>
						</td>
						<td>
							<?php echo $l->town; ?>
						</td>
						<td>
							<?php echo $l->country; ?>
						</td>
						<td>
                            <?php echo $l->cat_name; ?>
						</td>
						<td>
							<?php echo $l->gmlat; ?>
						</td>
						<td>
							<?php echo $l->gmlng; ?>
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

	<input type="hidden" name="task" value=""  />
	<input type="hidden" name="boxchecked" value="0"  />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>