<?php


defined('JPATH_BASE') or die;

$language = JFactory::getLanguage();
$language->load('com_puntos', JPATH_ADMINISTRATOR, 'en-GB', true);
$language->load('com_puntos', JPATH_ADMINISTRATOR, $language->getDefault(), true);
$language->load('com_puntos', JPATH_ADMINISTRATOR, null, true);

class JFormFieldPuntosLanguage extends JFormField
{
	
	protected $type = 'PuntosLanguage';

	
	protected function getInput()
	{
		return '';
	}
}
