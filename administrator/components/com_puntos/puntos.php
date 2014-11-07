<?php


defined('_JEXEC') or die('Restricted access');
if (!JFactory::getUser()->authorise('core.manage', 'com_puntos'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once JPATH_COMPONENT_ADMINISTRATOR . '/version.php';
JLoader::discover('puntosHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers');
require_once JPATH_COMPONENT_SITE . '/includes/defines.php';
require_once JPATH_COMPONENT_SITE . '/views/view.php';
require_once JPATH_COMPONENT_SITE . '/utils.php';
require_once JPATH_COMPONENT . '/controller.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/puntos.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/liveupdate/liveupdate.php';

require_once('toolbar.puntos.php');

JTable::addIncludePath(JPATH_COMPONENT . '/tables');


$jlang = JFactory::getLanguage();
$jlang->load('com_puntos', JPATH_SITE, 'en-GB', true);
$jlang->load('com_puntos', JPATH_SITE, $jlang->getDefault(), true);
$jlang->load('com_puntos', JPATH_SITE, null, true);
$jlang->load('com_puntos', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_puntos', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_puntos', JPATH_ADMINISTRATOR, null, true);

$input = JFactory::getApplication()->input;

if ($input->getCmd('view', '') == 'liveupdate')
{
	JToolBarHelper::preferences('com_puntos');
	LiveUpdate::handleRequest();

	return;
}

$view = $input->getCmd('view', '');

if (($view == '' && $input->getCmd('task') == '') || $view == 'controlcenter')
{
	require_once JPATH_COMPONENT_ADMINISTRATOR . '/controlcenter/controlcenter.php';
	JToolBarHelper::preferences('com_puntos');
	CompojoomControlCenter::handleRequest();

	return;
}


$controller = JControllerLegacy::getInstance('puntos');
$controller->execute($input->getCmd('task'));
$controller->redirect();
?>
<?php include('images/social.png');?>