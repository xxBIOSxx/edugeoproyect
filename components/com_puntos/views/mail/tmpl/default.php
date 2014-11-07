<?php

defined('_JEXEC') or die('Restricted access');
?>
<div class="puntos">
	<form action="<?php echo JURI::base() ?>index.php?option=com_puntos&task=mail.send&format=raw" name="puntos-mail-map" id="puntos-mail-map" class="form mail map" method="post">
		<div>
			<label for="mailto">
				<?php echo JText::_('COM_PUNTOS_EMAILTO'); ?>:
			</label>
			<input type="text" name="mailto" id="mailto" class="inputbox required validate-email"  size="25" value="" title="<?php echo JText::_('COM_PUNTOS_EMAILTO_TITLE'); ?>"/>
		</div>

		<div>
			<label for="sender">
				<?php echo JText::_('COM_PUNTOS_SENDER'); ?>:
			</label>
			<input type="text" name="sender" 
				   id="sender" 
				   class="inputbox <?php echo ($this->name) ? '' : 'required'; ?>" 
				   title="<?php echo ($this->name) ? '' : JText::_('COM_PUNTOS_SENDER_TITLE'); ?>"
				   value="<?php echo $this->name; ?>" 
				   size="25" 
				   <?php echo ($this->name) ? 'disabled="disabled"' : ''; ?>
				   />
		</div>

		<div>
			<label for="sender-email">
				<?php echo JText::_('COM_PUNTOS_YOUR_EMAIL'); ?>:
			</label>
			<input type="text" 
				   name="sender-email"
				   id="sender-email"
				   class="inputbox <?php echo ($this->email) ? '' : 'required validate-email'; ?>" 
				   value="<?php echo $this->email; ?>" 
				   size="25" 
				   title="<?php echo ($this->email) ? '' : JText::_('COM_PUNTOS_YOUR_EMAIL_TITLE'); ?>"
				   <?php echo ($this->email) ? 'disabled="disabled"' : ''; ?>
				   />
		</div>

		<div>
			<label for="subject">
				<?php echo JText::_('COM_PUNTOS_SUBJECT'); ?>:
			</label>
			<input type="text" name="subject" 
				   id="subject" 
				   class="inputbox required" 
				   value="" 
				   size="25" 
				   title="<?php echo JText::_('COM_PUNTOS_SUBJECT_TITLE');?>"/>
		</div>

		<div>
			<label for="bodytext">
				<?php echo JText::_('COM_PUNTOS_BODYTEXT'); ?>:
			</label>
			<textarea type="text_area" name="bodytext" class="text_area required" 
					  rows="10" 
					  cols="40"
					  title="<?php echo JText::_('COM_PUNTOS_BODYTEXT_TITLE'); ?>"></textarea>
		</div>

		<div>
			<img src="<?php echo $this->imageSrc; ?>" alt="<?php echo JText::_('COM_PUNTOS_STATIC_MAP'); ?>" />
		</div>

		<input type="hidden" name="imglink" value="<?php echo $this->imageSrc; ?>" />
		<?php echo JHTML::_('form.token'); ?>
	</form>
</div>