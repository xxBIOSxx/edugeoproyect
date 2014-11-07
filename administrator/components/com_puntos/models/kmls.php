<?php

defined('_JEXEC') or die();
jimport('joomla.application.component.modellist');

class PuntosModelKmls extends JModelList {

   
    protected $text_prefix = 'COM_PUNTOS';


	protected function populateState($ordering = null, $direction = null)
	{

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		parent::populateState('kmls.title', 'asc');
	}

	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$query->select('kmls.*, cat.cat_name , u.name as user_name')
			->from('#__puntos_kmls AS kmls')
			->leftJoin('#__puntos_categorie as cat ON cat.id = kmls.catid')
			->leftJoin('#__users as u ON u.id = kmls.created_by');

		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('kmls.state = '.(int) $published);
		} elseif ($published === '') {
			$query->where('(kmls.state IN (0, 1))');
		}

		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('kmls.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(kmls.name LIKE '.$search.')');
			}
		}

		$orderCol	= $this->state->get('list.ordering', 'kmls.title');
		$orderDirn	= $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;

	}


}