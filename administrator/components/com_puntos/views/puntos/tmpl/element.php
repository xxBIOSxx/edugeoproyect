<?php

defined('_JEXEC') or die('restricted access');
JHTML::_('behavior.tooltip');
?>
        <form action="index.php" method="post" name="adminForm">
       <table>
		<tr>
			<td align="left" width="100%"><?php echo JText::_( 'COM_PUNTOS_FILTER' ); ?>:
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'COM_PUNTOS_GO' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'COM_PUNTOS_RESET' ); ?></button>
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
        <table class="adminlist">
          <thead>
            <tr>
	          <th width="5"><?php echo JText::_( 'COM_PUNTOS_NUM' ); ?></th>
              <th width="5">
              <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->list ); ?>);" />
              </th>
              <th class="title"><?php echo JHTML::_('grid.sort',  'Title', 'cc.name', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>          
              <th width="15%"><?php echo JHTML::_('grid.sort',  'street', 'cc.street', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
              <th width="5%"><?php echo JHTML::_('grid.sort',  'plz', 'cc.plz', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
              <th width="10%"><?php echo JHTML::_('grid.sort',  'town', 'cc.town', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
              <th width="10%"><?php echo JHTML::_('grid.sort',  'country', 'cc.country', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
              <th width="10%"><?php echo JHTML::_('grid.sort',  'category', 'cc.catid', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
              <th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'Published', 'cc.published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
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
          foreach($this->list as $l)
          {
                  $checked 	= JHTML::_('grid.id', $i, $l->id );
                  $published    = JHTML::_('grid.published', $l, $i );
                  $link = JRoute::_( 'index.php?option=com_puntos&task=edit&cid[]=' . $l->id );
             ?>
              <tr class="<?php echo "row".$i%2; ?>">
                <td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
                <td>
                  <?php echo $checked; ?>
                </td>
                <td>
					
                  <a onclick="window.parent.selectPunto('<?php echo $l->id; ?>', '<?php echo str_replace(array("'", "\""), array("\\'", ""),$l->name); ?>', '<?php echo JRequest::getVar('object'); ?>');"><?php echo $l->name; ?></a>
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
          <input type="hidden" name="option" value="com_puntos"  />
          <input type="hidden" name="task" value=""  />
          <input type="hidden" name="boxchecked" value="0"  />
          <input type="hidden" name="controller" value="puntos" /> 
          <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
		  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
          <?php echo JHTML::_( 'form.token' ); ?>