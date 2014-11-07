<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');


class PuntosModelKmls extends JModelLegacy
{
	private $catid = '';

	
	public function __construct()
	{
		parent::__construct();
		$this->catid = explode(';', JFactory::getApplication()->input->getString('cat', 1));
	}


	public function getKmls()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$where = '';

		foreach ($this->catid as $cat)
		{
			$where[] = $db->quote($cat);
		}

		$query->select('*')
			->from('#__Puntos_kmls')
			->where('catid IN (' . implode(',', $where) . ')')
			->where('state = 1');

		$db->setQuery($query);

		return $db->loadObjectList();
	}
}
