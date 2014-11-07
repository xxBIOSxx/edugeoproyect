<?php


defined('_JEXEC') or die('Restricted access');

$language = JFactory::getLanguage();
$language->load('com_puntos.sys', JPATH_ADMINISTRATOR, 'en-GB', true);
$language->load('com_puntos.sys', JPATH_ADMINISTRATOR, $language->getDefault(), true);
$language->load('com_puntos.sys', JPATH_ADMINISTRATOR, null, true);

$view = JFactory::getApplication()->input->getCmd('view');

$subMenus = array(
	'controlcenter' => 'COM_PUNTOS_DASHBOARD',
	'puntos' => 'COM_PUNTOS_LOCATIONS',
	'categories' => 'COM_PUNTOS_CATEGORIES',
	'import' => 'COM_PUNTOS_IMPORT',
	'liveupdate' => 'COM_PUNTOS_LIVEUPDATE'
);

if (PUNTOS_PRO)
{
	$subMenus['kmls'] = 'COM_PUNTOS_KML';
}

if (!JFactory::getUser()->authorise('core.admin', 'com_puntos'))
{
	unset($subMenus['import']);
	unset($subMenus['liveupdate']);
}

foreach ($subMenus as $key => $name)
{
	$active = ($view == $key);
	JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_puntos&view=' . $key, $active);
}
