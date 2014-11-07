<?php


defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

require_once(JPATH_COMPONENT_ADMINISTRATOR . '/libraries/maps/utility.php');


class puntosModelTile extends JModelLegacy
{

	public function getItems($cat = true)
	{
		$input = JFactory::getApplication()->input;
		$cats = $input->getString('cats', '', 'get');
		$search = $input->getString('search', '', 'get');
		$lang = $input->getString('hs-language');
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());
		$x = $input->getInt('x', 0, 'get');
		$y = $input->getInt('y', 0, 'get');
		$z = $input->getInt('z', 0, 'get');

		$rect = puntosMapUtility::getTileRect($x, $y, $z);

		$swlat = $rect->y;
		$swlng = $rect->x;
		$nelat = $swlat + $rect->height;
		$nelng = $swlng + $rect->width;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('m.id'))
			->select($db->quoteName('gmlng', 'lng'))
			->select($db->quoteName('gmlat', 'lat'))
			->select($db->quoteName('name', 'nm'));

		if ($cat)
		{
			$query->select($db->quoteName('catid'));
		}

		$query->from($db->qn('#__puntos_marker', 'm'));

		$query->where('(' . $db->qn('gmlng') . ' > ' . $db->q($swlng) . ' AND ' . $db->qn('gmlng') . '<' . $db->q($nelng) . ')');
		$query->where('(' . $db->qn('gmlat') . ' <= ' . $db->q($nelat) . ' AND ' . $db->qn('gmlat') . '>=' . $db->q($swlat) . ')');

		if ($cats != '')
		{
			$cats = explode(';', $cats);

			foreach ($cats as $key => $cat)
			{
				$cats[$key] = $db->quote($cat);
			}

			$query->where($db->quoteName('catid') . ' IN (' . implode(',', $cats) . ')');
		}

		if ($search != '')
		{
			$query->leftJoin(
				$db->quoteName('#__puntos_categorie') . ' AS ' . $db->quoteName('c')
			. 'ON ' . $db->quoteName('m.catid') . ' = ' . $db->qn('c.id')
			);
			$this->buildSearchWhere($search, $query);
		}

		$query->where($db->qn('m.published') . ' = ' . $db->q(1));

		$nullDate = $db->Quote($db->getNullDate());
		$nowDate = $db->Quote(JFactory::getDate()->toSQL());

		$query->where('(' . $db->qn('m.publish_up') . ' = ' . $nullDate . ' OR ' . $db->qn('m.publish_up') . ' <= ' . $nowDate . ')');
		$query->where('(' . $db->qn('m.publish_down') . ' = ' . $nullDate . ' OR ' . $db->qn('m.publish_down') . ' >= ' . $nowDate . ')');
		$query->where('m.access IN (' . $groups . ')');

		if ($lang)
		{
			$query->where('m.language in (' . $query->quote($lang) . ',' . $query->quote('*') . ')');
		}

		$query->order('RAND(1)');

		$db->setQuery($query, 0, 500);

		return $db->loadAssocList();
	}


	protected function buildSearchWhere($sentence, &$query)
	{
		$db = JFactory::getDbo();

		$name = $db->quoteName('m.name');
		$description = $db->quoteName('m.description');
		$descriptionSmall = $db->quoteName('m.description_small');
		$plz = $db->quoteName('m.plz');
		$catName = $db->quoteName('c.cat_name');
		$street = $db->quoteName('m.name');
		$country = $db->quoteName('m.name');
		$town = $db->quoteName('m.name');
		$and = array();

		if (preg_match('/"([^"]+)"/', $sentence, $m))
		{
		
			$searchWord = $db->Quote('%' . $db->escape(trim($m[1]), true) . '%', false);


			$search[] = $name . ' LIKE ' . $searchWord;
			$search[] = $description . ' LIKE ' . $searchWord;
			$search[] = $descriptionSmall . ' LIKE ' . $searchWord;
			$search[] = $plz . ' LIKE ' . $searchWord;
			$search[] = $catName . ' LIKE ' . $searchWord;
			$search[] = $street . ' LIKE ' . $searchWord;
			$search[] = $country . ' LIKE ' . $searchWord;
			$search[] = $town . ' LIKE ' . $searchWord;

			$word = trim(str_replace('"' . $m[1] . '"', '', $sentence));

			if ($word)
			{
				$searchWord = $db->Quote('%' . $db->escape(trim($word), true) . '%', false);
				$and[] = $name . ' LIKE ' . $searchWord;
				$and[] = $description . ' LIKE ' . $searchWord;
				$and[] = $descriptionSmall . ' LIKE ' . $searchWord;
				$and[] = $plz . ' LIKE ' . $searchWord;
				$and[] = $catName . ' LIKE ' . $searchWord;
				$and[] = $street . ' LIKE ' . $searchWord;
				$and[] = $country . ' LIKE ' . $searchWord;
				$and[] = $town . ' LIKE ' . $searchWord;
			}
		}
		else
		{
			$words = explode(' ', $sentence);

			foreach ($words as $word)
			{
				$searchWord = $db->Quote('%' . $db->escape($word, true) . '%', false);
				$search[] = $name . ' LIKE ' . $searchWord;
				$search[] = $description . ' LIKE ' . $searchWord;
				$search[] = $descriptionSmall . ' LIKE ' . $searchWord;
				$search[] = $plz . ' LIKE ' . $searchWord;
				$search[] = $catName . ' LIKE ' . $searchWord;
				$search[] = $street . ' LIKE ' . $searchWord;
				$search[] = $country . ' LIKE ' . $searchWord;
				$search[] = $town . ' LIKE ' . $searchWord;
			}
		}

		$query->where('(' . implode(' OR ', $search) . ')');

		if (count($and))
		{
			$query->where('(' . implode(' OR ', $and) . ')');
		}

	}
}
