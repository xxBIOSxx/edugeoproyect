<?php


defined('_JEXEC') or die();
jimport('joomla.application.component.modeladmin');

class PuntosModelCategory extends JModelAdmin
{

	public function getTable($type = 'Categorie', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_puntos.categorie', 'categorie', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}


	
	protected function loadFormData()
	{

		$data = JFactory::getApplication()->getUserState('com_puntos.edit.punto.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

	
			if ($this->getState('category.id') == 0)
			{
				$app = JFactory::getApplication();
				$data->set('catid', JRequest::getInt('catid', $app->getUserState('com_puntos.punto.filter.category_id')));
			}
		}

		return $data;
	}

	public function getCategories()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, cat_name, cat_description, cat_icon, cat_shadowicon, count')
			->from('#__puntos_categorie');
		$db->setQuery($query);

		return $db->loadObjectList('id');
	}
}
