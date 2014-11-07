<?php


defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSearchpuntos extends JPlugin {


	public function __construct(& $subject, $config) {

		parent::__construct($subject, $config);
		$this->loadLanguage();
	}


	function onSearchAreas() {
		return $this->areas();
	}

	function onSearch($text, $phrase='', $ordering='', $areas=null) {
		return $this->search($text, $phrase, $ordering, $areas);
	}


	function onContentSearchAreas() {
		return $this->areas();
	}

	function onContentSearch($text, $phrase='', $ordering='', $areas=null) {
		return $this->search($text, $phrase, $ordering, $areas);
	}


	private function areas() {
		static $areas = array(
			'puntos' => 'puntos'
		);
		return $areas;
	}

	private function search($text, $phrase='', $ordering='', $areas=null) {
		if (!$text) {
			return array();
		}
		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys(botSearchpuntosAreas()))) {
				return array();
			}
		}

		$db = & JFactory::getDBO();
		if ($phrase == 'exact') {
			$text = $db->Quote('%' . $db->escape($text, true) . '%', false);
			$where = "(LOWER(m.name) LIKE $text)
			   OR (LOWER(m.description_small) LIKE $text)" .
					" OR (LOWER(m.description) LIKE $text) 
				 OR (LOWER(m.street) LIKE $text)" .
					" OR (LOWER(m.plz) LIKE $text)";
		} else {
			$words = explode(' ', $text);
			$wheres = array();
			foreach ($words as $word) {
				$word = $db->Quote('%' . $db->escape($word, true) . '%', false);
				$wheres[] = "(LOWER(m.name) LIKE $word)
					   OR (LOWER(m.description_small) LIKE $word)" .
						" OR (LOWER(m.description) LIKE $word) 
		  			   OR (LOWER(m.street) LIKE $word)" .
						" OR (LOWER(m.plz) LIKE $word)";
			}
			if ($phrase == 'all') {
				$seperator = "AND";
			} else {
				$seperator = "OR";
			}
			$where = '(' . implode(") $seperator (", $wheres) . ')';
		}
		$where .= ' AND c.published = 1 AND m.published = 1';

		switch ($ordering) {
			case 'oldest':
				$order = 'm.created ASC';
				break;
			case 'alpha':
				$order = 'm.name ASC';
				break;
			case 'newest':
			default:
				$order = 'm.created DESC';
				break;
		}

		$pluginParams = $this->params;
		$limit = $pluginParams->get('search_limit', 50);

		$query = "SELECT m.id, m.name AS title, m.description_small AS text, m.created AS created, m.catid, " .
				" 'puntos' AS section," .
				" c.cat_name, " .
				" '2' AS browsernav" .
				' FROM ' . $db->qn('#__puntos_marker') . ' AS m' .
				' LEFT JOIN ' . $db->qn('#__puntos_categorie') . ' AS c' .
				' ON m.catid = c.id ' .
				" WHERE $where" .
				" ORDER BY $order";
		$db->setQuery($query, 0, $limit);
		$rows = $db->loadObjectList();

		if (is_array($rows)) {
			foreach ($rows as $key => $row) {
				$urlcat = $row->catid . ':' . JFilterOutput::stringURLSafe($row->cat_name);
				$urlid = $row->id . ':' . JFilterOutput::stringURLSafe($row->title);
				$itemId = $this->getpuntosItemid('com_puntos');
				$rows[$key]->href = JRoute::_("index.php?option=com_puntos&view=hotspot&catid=" . $urlcat . "&id=" . $urlid . '&Itemid=' . $itemId);
			}
		}

		return $rows;
	}


	function getpuntosItemid($component='') {
		static $ids;
		if (!isset($ids)) {
			$ids = array();
		}
		if (!isset($ids[$component])) {
			$database = & JFactory::getDBO();
			$query = "SELECT id FROM #__menu"
					. "\n WHERE link LIKE '%option=$component%'"
					. "\n AND type = 'component'"
					. "\n AND published = 1 LIMIT 1";
			$database->setQuery($query);
			$ids[$component] = $database->loadResult();
		}
		return $ids[$component];
	}

}