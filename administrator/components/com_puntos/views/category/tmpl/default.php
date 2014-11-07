<?php


defined('_JEXEC') or die('Restricted access');

$editor = JFactory::getEditor();

JHTML::_('behavior.framework', true);
JHTML::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.colorpicker');
JHTML::_('stylesheet', 'media/com_puntos/css/puntos-backend.css');

?>
<script type="text/javascript">
	Joomla.submitbutton = function (button) {
		if (button != 'category.cancel') {
			var validator = new Form.Validator.Inline(document.id('adminForm'), {wrap: true});
			if (validator.validate()) {
				var selected = document.id('select-icon').get('value');
				if (!document.id('category-icon').getElement('img') && selected != 'delete' && selected != 'new') {
					document.id('category-icon').addClass('validation-failed');
					return false;
				}
				Joomla.submitform(button);
				return true;
			}
			;
			return false;
		}
		Joomla.submitform(button);
	}
</script>

<div id="puntos" class="puntos">
	<form action="<?php echo JRoute::_('index.php?option=com_puntos&view=category&id=' . (int)$this->row->id); ?>" method="post" name="adminForm" id="adminForm" class="form" enctype="multipart/form-data">
		<div class="width-60 span8 fltlft">
			<fieldset class="adminform">
				<legend><?php echo empty($this->row->id) ? JText::_('COM_PUNTOS_NEW_CATEGORY') : JText::sprintf('COM_PUNTOS_EDIT_CATEGORY', $this->row->id); ?></legend>
				<ul class="adminformlist">
					<li><?php echo $this->form->getLabel('cat_name'); ?>
						<?php echo $this->form->getInput('cat_name'); ?></li>
					<li><?php echo $this->form->getLabel('id'); ?>
						<?php echo $this->form->getInput('id'); ?></li>

					<li><?php echo $this->form->getLabel('published'); ?>
						<?php echo $this->form->getInput('published'); ?></li>

					<li><div class="clr"></div>
						<?php echo $this->form->getLabel('cat_description'); ?>
						<?php echo $this->form->getInput('cat_description'); ?></li>

					<li><?php echo $this->form->getLabel('cat_icon'); ?>
						<div style="float:left;">
							<?php echo $this->form->getInput('cat_icon'); ?>
						</div>
						<div style="clear:both"></div>
					</li>

					<li><?php echo $this->form->getLabel('cat_date'); ?>
						<?php echo $this->form->getInput('cat_date'); ?></li>

					<?php echo $this->loadTemplate('params'); ?>
				</ul>


			</fieldset>
		</div>

		<input type="hidden" name="id" value="<?php echo $this->row->id; ?>"/>
		<input type="hidden" name="option" value="com_puntos"/>
		<input type="hidden" name="jform[wsampleicon]" id="wsampleicon" value=""/>
		<input type="hidden" name="jform[deleteicon]" id="deleteicon" value=""/>
		<input type="hidden" name="task" value=""/>
	</form>
</div>