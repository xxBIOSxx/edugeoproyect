<?php


defined('_JEXEC') or die ('Restricted access');

jimport('joomla.filesystem.file');
require_once JPATH_COMPONENT_ADMINISTRATOR . '/version.php';
JLoader::discover('puntosHelper', JPATH_COMPONENT . '/helpers');
require_once JPATH_COMPONENT . '/utils.php';
require_once JPATH_COMPONENT . '/includes/defines.php';
require_once JPATH_ADMINISTRATOR . '/components/com_puntos/helpers/puntos.php';
require_once JPATH_COMPONENT . '/views/view.php';
JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_puntos/tables');


$jlang = JFactory::getLanguage();
$jlang->load('com_puntos', JPATH_SITE, 'en-GB', true);
$jlang->load('com_puntos', JPATH_SITE, $jlang->getDefault(), true);
$jlang->load('com_puntos', JPATH_SITE, null, true);
$jlang->load('com_puntos', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_puntos', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_puntos', JPATH_ADMINISTRATOR, null, true);

$controller = JControllerLegacy::getInstance('puntos');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();
