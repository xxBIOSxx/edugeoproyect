<?php


defined('_JEXEC') or die;

$fieldSets = $this->form->getFieldsets('params');

foreach ($fieldSets as $name => $fieldSet) : ?>
	<?php foreach ($this->form->getFieldset($name) as $field) : ?>
		<li style="clear: both"><?php echo $field->label; ?>
			<?php echo $field->input; ?>
		</li>
	<?php endforeach; ?>
<?php endforeach; ?>
