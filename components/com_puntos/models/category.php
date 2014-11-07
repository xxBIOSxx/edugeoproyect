<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class puntosModelCategory extends JModelLegacy {

	private $_category = null;
	private $_id = null;

	public function __construct() {
		parent::__construct();
		
		$this->_id = JRequest::getInt('cat', false);
	}

	public function getCategory($id = null) {
		if (!$this->_category) {
			if($id === null) {
				$id = $this->_id;
			}
			$query = 'SELECT * FROM ' . $this->_db->qn('#__puntos_categorie')
					. ' WHERE id = ' . $this->_db->Quote($id);
			$this->_db->setQuery($query, 0, 1);
			$this->_category = $this->_db->loadObject();
		}
		
		return $this->_category;
	}

    public function getCategories() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, cat_name, cat_description, cat_icon, cat_shadowicon, params, count')
            ->from('#__puntos_categorie')
            ->where('published = 1');
        $db->setQuery($query);
        return $db->loadObjectList('id');
    }
	
}