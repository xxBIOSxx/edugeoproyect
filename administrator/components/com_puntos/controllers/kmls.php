<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controlleradmin');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/puntos.php';

class PuntossControllerKmls extends JControllerAdmin
{
	
	public function __construct()
	{
		parent::__construct();

	
		$this->registerTask('add', 'edit');
		$this->registerTask('apply', 'save');
		$this->registerTask('unpublish', 'publish');
	}

	public function getModel($name = 'Kml', $prefix = 'puntosModel')
	{
		$model = parent::getModel($name, $prefix);
		return $model;
	}

	public function remove()
	{
		$cid = JRequest::getVar('cid', array(), '', 'array');
		$db = & JFactory::getDBO();

		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = "DELETE FROM #__puntos_kmls WHERE puntos_kml_id IN ( $cids )";
			$db->setQuery($query);

			if (!$db->query())
			{
				echo "<script> alert ('" . $db->getErrorMsg() . "');
			window.history.go(-1); </script>\n";
			}
		}

		$this->setRedirect('index.php?option=com_puntos&view=kmls');
	}



	private function getCategories()
	{
		$db = & JFactory::getDBO();
		$query = "SELECT * FROM #__puntos_categorie ORDER BY " . puntosHelper::getSettings('category_ordering', 'id ASC');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
}
