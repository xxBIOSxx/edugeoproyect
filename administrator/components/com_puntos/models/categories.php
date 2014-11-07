<?php

defined('_JEXEC') or die();
jimport('joomla.application.component.modellist');

class PuntosModelCategories extends JModelList {

	var $_categories = null;
	var $_total = null;
	var $_pagination = null;

	public function __construct() {
		parent::__construct();
		$appl = JFactory::getApplication();
		$context = 'com_puntos.categories.list.';
		$limit = $appl->getUserStateFromRequest('global.list.limit', 'limit', $appl->getCfg('list_limit'), 'int');
		$limitstart = $appl->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	public function getList() {
		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	}

	public function getTotal() {
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	public function getPagination() {
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_pagination;
	}

	private function _buildQuery() {
		$context = 'com_puntos.categories.list.';
		$where = $this->_buildContentWhere($context);
		$orderby = $this->_buildContentOrderBy($context, 'cc.cat_name');

		$query = ' SELECT cc.* FROM #__puntos_categorie AS cc '
				. $where
				. $orderby
		;

		return $query;
	}

	private function _buildContentOrderBy($context, $cc_or_a) {

		$appl = JFactory::getApplication();
		$filter_order = $appl->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'cc.cat_name', 'cmd'); // Category tree works with id not with ordering
		$filter_order_dir = $appl->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');

		if ($filter_order == 'cc.cat_name') {
			$orderby = ' ORDER BY  cc.cat_name ' . $filter_order_dir;
		} else {
			$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_dir;
		}
		return $orderby;
	}

	private function _buildContentWhere($context) {
		$appl = JFactory::getApplication();
		$filter_state = $appl->getUserStateFromRequest($context . 'filter_state', 'filter_state', '', 'word');
		$search = $appl->getUserStateFromRequest($context . 'search', 'search', '', 'string');
		$search = JString::strtolower($search);
		$where = array();

		if ($search) {
			$where[] = 'LOWER(cc.cat_name) LIKE ' . $this->_db->Quote('%' . $search . '%');
		}
		if ($filter_state) {
			if ($filter_state == 'P') {
				$where[] = 'cc.published = 1';
			} else if ($filter_state == 'U') {
				$where[] = 'cc.published = 0';
			}
		}

		$where = ( count($where) ? ' WHERE ' . implode(' AND ', $where) : '' );

		return $where;
	}

   
    public function getCategories() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, cat_name, cat_description, cat_icon, cat_shadowicon, count')
            ->from('#__puntos_categorie');
        $db->setQuery($query);
        return $db->loadObjectList('id');
    }


}