<?php


defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ADMINISTRATOR . '/components/com_Puntos/tables/marker.php');

class JElementPunto extends JElement {


	var $_name = 'Punto';

	public function fetchElement($name, $value, &$node, $control_name) {
		$appl = JFactory::getApplication();

		$db = & JFactory::getDBO();
		$doc = & JFactory::getDocument();
		$template = $appl->getTemplate();
		$fieldName = $control_name . '[' . $name . ']';
		$punto = & JTable::getInstance('marker', 'Table');
		if ($value) {
			$punto->load($value);
		} else {
			$punto->name = JText::_('COM_PUNTOS_SELECT_PUNTO');
		}

		$js = "
		
		function selectPunto(id, name, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = name;
			document.getElementById('sbox-window').close();
		}";
		$doc->addScriptDeclaration($js);

		$link = 'index.php?option=com_puntos&amp;controller=all&layout=element&amp;tmpl=component&amp;object=' . $name;

		JHTML::_('behavior.modal', 'a.modal');
		
		$html = '<script language="javascript" type="text/javascript">
				<!--
				function submitbutton(pressbutton) {
							var form = document.adminForm;
							var type = form.type.value;

							if (pressbutton == "cancelItem") {
								submitform( pressbutton );
								return;
							}
							if ( trim( form.name.value ) == "" ){
								alert( "' .  JText::_( 'COM_PUNTOS_ITEM_MUST_HAVE_A_TITLE', true ) . '");
							} else if( document.getElementById("id_id").value == 0 ){
								alert( "' . JText::_('COM_PUNTOS_PLEASE_SELECT_PUNTO', true ) . '");
							} else {
								submitform( pressbutton );
							}
						}
				//-->
				</script>';
		$html .= "\n" . '<div style="float: left;"><input style="background: #ffffff;" type="text" id="' . $name . '_name" value="' . htmlspecialchars($punto->name, ENT_QUOTES, 'UTF-8') . '" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="' . JText::_('COM_PUNTOS_SELECT_PUNTO') . '"  href="' . $link . '" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">' . JText::_('COM_PUNTOS_SELECT') . '</a></div></div>' . "\n";
		$html .= "\n" . '<input type="hidden" id="' . $name . '_id" name="' . $fieldName . '" value="' . (int) $value . '" />';

		return $html;
	}

}
