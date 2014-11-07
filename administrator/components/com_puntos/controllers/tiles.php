<?php


defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerlegacy');

class PuntosControllerTiles extends JControllerLegacy
{

	public function delete()
	{
		jimport('joomla.filesystem.folder');
		$appl = JFactory::getApplication();
		$path = JPATH_ROOT . '/media/com_puntos/tiles';
		$files = JFolder::files($path, false, true, array('index.html'));
		$msg = 'COM_PUNTOS_TILES_SUCCESSFULLY_DELETED';

		if (!empty($files))
		{
			if (JFile::delete($files) !== true)
			{
				$msg = 'COM_PUNTOS_TILES_DELETE_UNSUCCESSFUL';
			}
		}

		$appl->redirect('index.php?option=com_puntos&view=controlcenter', JText::_($msg));
	}
}