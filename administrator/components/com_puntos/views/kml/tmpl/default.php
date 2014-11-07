<?php

defined('_JEXEC') or die('Restricted access');

$editor = JFactory::getEditor();

JHTML::_('behavior.framework');
JHTML::_('behavior.tooltip');

JHTML::_('stylesheet', 'puntos-backend.css', 'media/com_puntos/css/');

?>

<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_puntos&view=kml&puntos_kml_id=' . (int)$this->item->puntos_kml_id); ?>" method="post" class="form" name="adminForm" id="adminForm" >
    <div class="width-60 span8 fltlft">
        <fieldset class="adminform">
            <legend><?php echo empty($this->item->puntos_kml_id) ? JText::_('COM_PUNTOS_NEW_KML') : JText::sprintf('COM_PUNTOS_EDIT_KML', $this->item->puntos_kml_id); ?></legend>
            <ul class="adminformlist">
                <li>
                    <?php echo $this->form->getLabel('title'); ?>
                    <?php echo $this->form->getInput('title'); ?>
                </li>
                <li>
                    <?php echo $this->form->getLabel('catid'); ?>
                    <?php echo $this->form->getInput('catid'); ?>
                </li>
                <li>
                    <?php echo $this->form->getLabel('state'); ?>
                    <?php echo $this->form->getInput('state'); ?>
                </li>
                <li>
                    <?php echo $this->form->getLabel('puntos_kml_id'); ?>
                    <?php echo $this->form->getInput('puntos_kml_id'); ?>
                </li>
				<?php if($this->item->mangled_filename) : ?>
					<li>
						<label><?php echo JText::_('COM_PUNTOS_CURRENT_KML_FILE'); ?>:</label>
						<div class="fltlft">
							<a href="<?php echo JURI::root() . 'media/com_puntos/kmls/'. $this->item->mangled_filename; ?>">
								<?php echo $this->item->original_filename; ?>
							</a>
						</div>
					</li>
				<?php endif; ?>
                <li>
                    <?php echo $this->form->getLabel('kml_file'); ?>
                    <?php echo $this->form->getInput('kml_file'); ?>
                </li>
            </ul>

            <div class="clr"></div>
            <?php echo $this->form->getLabel('description'); ?>
            <div class="clr"></div>
            <?php echo $this->form->getinput('description'); ?>

        </fieldset>
    </div>
    <div class="width-40 span4 fltrt">
        <?php echo JHtml::_('sliders.start', 'content-sliders-' . $this->item->puntos_kml_id, array('useCookie' => 1)); ?>

        <?php echo JHtml::_('sliders.panel', JText::_('COM_PUNTOS_FIELDSET_PUBLISHING'), 'publishing-details'); ?>
        <fieldset class="panelform">
            <ul class="adminformlist">
                <li><?php echo $this->form->getLabel('created_by'); ?>
                    <?php echo $this->form->getInput('created_by'); ?></li>

                <li><?php echo $this->form->getLabel('created'); ?>
                    <?php echo $this->form->getInput('created'); ?></li>

            </ul>
        </fieldset>

        <?php echo JHtml::_('sliders.end'); ?>
    </div>
    <input type="hidden" name="task" value=""/>
    <?php echo JHTML::_('form.token'); ?>
</form>