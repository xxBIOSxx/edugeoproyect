<?php


defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');
require_once(JPATH_ADMINISTRATOR . '/components/com_puntos/tables/marker.php');


class JFormFieldModal_Punto extends JFormField
{

	protected $type = 'Modal_Punto';


	protected function getInput()
	{
	
		JHtml::_('behavior.modal', 'a.modal');

		$db	= JFactory::getDBO();
		$db->setQuery(
			'SELECT name' .
			' FROM #__puntos_marker' .
			' WHERE id = '.(int) $this->value
		);
		$title = $db->loadResult();

		if ($error = $db->getErrorMsg()) {
			JError::raiseWarning(500, $error);
		}

		if (empty($title)) {
			$title = JText::_('COM_PUNTOS_SELECT_PUNTO');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		
		$script[] = '	function selectPunto(id, title, object) {';
		$script[] = '		document.id("'.$this->id.'_id").value = id;';
		$script[] = '		document.id("'.$this->id.'_name").value = title;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
		
		$link = 'index.php?option=com_puntos&amp;view=puntos&layout=element&amp;tmpl=component&amp;object=' . $this->name;
		
		$html[] = '<div class="fltlft">';
		$html[] = '  <input type="text" id="'.$this->id.'_name" value="'.$title.'" disabled="disabled" size="35" />';
		$html[] = '</div>';

	
		$html[] = '<div class="button2-left">';
		$html[] = '  <div class="blank">';
		$html[] = '	<a class="modal" title="'.JText::_('COM_CONTENT_CHANGE_ARTICLE').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">'.JText::_('COM_PUNTOS_SELECT_PUNTO').'</a>';
		$html[] = '  </div>';
		$html[] = '</div>';
		
	
		if (0 == (int)$this->value) {
			$value = '';
		} else {
			$value = (int)$this->value;
		}

	
		$class = '';
		if ($this->required) {
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';

		return  implode("\n", $html);
	}
}