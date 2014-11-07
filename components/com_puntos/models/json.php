<?php



defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');


class puntosModelJson extends JModelLegacy
{
	private $puntos = null;

	private $catid = null;


	public function __construct()
	{
		parent::__construct();
		$this->catid = JFactory::getApplication()->input->getString('cat', 1);
	}

	
	protected function populateState()
	{
		$app = JFactory::getApplication();
		$this->setState('filter.language', $app->getLanguageFilter());
	}

	
	public function getpuntos()
	{
		if (!$this->puntos)
		{
			$db = JFactory::getDBO();

			$offset = JFactory::getApplication()->input->getInt('offset');
			$cats = $this->getCats();

			$puntos = array();

			foreach ($cats as $cat)
			{
				$query = $db->getQuery(true);
				$query->select('m.id as puntos_id, m.*, u.name AS user_name')
					->from('#__puntos_marker as m')
					->leftJoin('#__users AS u ON u.id = m.created_by')
					->where(implode(' AND ', $this->buildWhereQuery($cat)))
					->order('m.' . puntosHelper::getSettings('puntos_order', 'name ASC'));

				$db->setQuery($query, $offset, puntosHelper::getSettings('marker_list_length', 20));
				$rows = $db->loadObjectList();
				$puntos = array_merge($puntos, $rows);
			}

			

			if (count($puntos))
			{
				foreach ($puntos as $value)
				{
					$this->puntos['puntos'][$value->catid][] = $value;
					$this->puntos['puntos'][$value->catid]['viewCount'] = $this->countpuntosInMapView($value->catid);
					$this->puntos['puntos'][$value->catid]['categoryCount'] = $this->countpuntosInCategory($value->catid);
				}
			}
			else
			{
				$cats = $this->getCats();

				foreach ($cats as $category)
				{
					$this->puntos['puntos'][$category]['viewCount'] = $this->countpuntosInMapView($category);
					$this->puntos['puntos'][$category]['categoryCount'] = $this->countpuntosInCategory($category);
				}
			}
		}

		return $this->puntos;
	}


	public function countPuntosInCategory($category)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('count')->from('#__puntos_categorie')
			->where('id = ' . $db->quote($category));
		$db->setQuery($query);

		return $db->loadObject()->count;
	}


	public function countPuntosInMapView($catId)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('COUNT(*) as count')
			->from('#__puntos_marker as m')
			->where(implode('  AND ', $this->buildWhereQuery($catId)));
		$db->setQuery($query);

		return $db->loadObject()->count;
	}


	
	private function getCats()
	{
		$cats = explode(';', $this->catid);
		$secure = array();

		if (is_array($cats))
		{
			foreach ($cats as $cat)
			{
				if (is_numeric($cat) && $cat > 0)
				{
					$secure[] = $cat;
				}
			}
		}

		return $secure;
	}

	
	public function buildWhereQuery($cat = null)
	{
		$db = JFactory::getDBO();
		$input = JFactory::getApplication()->input;
		$where = array();

		if ($cat === null)
		{
			$cats = $this->getCats();
			$secure = array();

			foreach ($cats as $cat)
			{
				$secure[] = $db->quote($cat);
			}

			$where[] = ' m.catid IN (' . implode(',', $secure) . ')';
		}
		else
		{
			$where[] = ' m.catid = ' . $db->quote($cat);
		}

		$level = $input->getInt('level');

		$levels = array(0, 1);
	
		if (!in_array($level, $levels))
		{
			$ne = $input->getString('ne');
			$sw = $input->getString('sw');
			list($nelat, $nelng) = explode(',', $ne);
			list($swlat, $swlng) = explode(',', $sw);

			
			if ($nelng > $swlng)
			{
				$where[] = ' (m.gmlng > ' . $db->quote($swlng) . ' AND m.gmlng < ' . $db->quote($nelng) . ')';
				$where[] = ' (m.gmlat <= ' . $db->quote($nelat) . ' AND m.gmlat >= ' . $db->quote($swlat) . ')';
			}
			else
			{
				$where[] = ' (m.gmlng >= ' . $db->quote($swlng) . ' OR m.gmlng <= ' . $db->quote($nelng) . ')';
				$where[] = ' (m.gmlat <= ' . $db->quote($nelat) . ' AND m.gmlat >= ' . $db->quote($swlat) . ')';
			}
		}

		$where[] = ' m.published = 1';

		$nullDate = $db->Quote($db->getNullDate());
		$nowDate = $db->Quote(JFactory::getDate()->toSQL());

		$where[] = ('(m.publish_up = ' . $nullDate . ' OR m.publish_up <= ' . $nowDate . ')');
		$where[] = ('(m.publish_down = ' . $nullDate . ' OR m.publish_down >= ' . $nowDate . ')');

		if ($this->getState('filter.language'))
		{
			$where[] = 'm.language in (' . $db->quote($input->getString('hs-language')) . ',' . $db->quote('*') . ')';
		}

		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());
		$where[] = 'm.access IN (' . $groups . ')';

		return $where;
	}
}
