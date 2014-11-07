<?php


defined('_JEXEC') or die();
jimport('joomla.application.component.modellist');


class PuntosModelPuntos extends JModelList
{
	
	protected $text_prefix = 'COM_PUNTOS';

	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'street', 'a.street',
				'plz', 'a.plz',
				'town', 'a.town',
				'country', 'a.country',
				'gmlat', 'a.gmlat',
				'gmlng', 'a.gmlng',
				'cat_name', 'cat.cat_name', 'category_title',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'created_by_alias', 'a.created_by_alias',
				'ordering', 'a.ordering',
				'published', 'a.published',
				'language', 'a.language',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down',
			);

		}

		parent::__construct($config);
	}

	
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id', '');
		$this->setState('filter.category_id', $categoryId);

		parent::populateState('a.name', 'asc');
	}

	
	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('a.*, cat.cat_name, u.name AS user_name')
			->from('#__puntos_marker AS a')
			->leftJoin('#__users AS u ON u.id = a.created_by')
			->leftJoin('#__puntos_categorie as cat ON cat.id = a.catid');

		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published IN (0, 1))');
		}

		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId))
		{
			$query->where('a.catid = ' . (int) $categoryId);
		}
		elseif (is_array($categoryId))
		{
			$query->where('a.catid IN (' . implode(',', $categoryId) . ')');
		}

		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('(a.name LIKE ' . $search . ')');
			}
		}

		$orderCol = $this->state->get('list.ordering', 'a.name');
		$orderDirn = $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
