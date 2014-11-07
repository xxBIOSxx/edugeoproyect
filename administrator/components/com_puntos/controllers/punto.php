<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controllerform');
require_once( JPATH_COMPONENT_ADMINISTRATOR .'/helpers/puntos.php');

class PuntosControllerPunto extends JControllerForm {

    public function __construct() {
        parent::__construct();

       
        $this->registerTask('add', 'edit');
    }

    public function getModel($name = 'Punto', $prefix = 'PuntosModel') {
        $model = parent::getModel($name, $prefix);
        return $model;
    }

    public function edit() {

        $document = & JFactory::getDocument();
        if (!$this->getCategories()) {
            $message = JText::_('COM_PUNTOS_CREATE_CATEGORIES_FIRST');
            $this->setRedirect('index.php?option=com_puntos&view=categories', $message, 'notice');
	        return;
        }

        parent::edit();

    }

    public function cancel() {
        $link = 'index.php?option=com_puntos&view=puntos';
        $this->setRedirect($link);
    }

    
    private function getCategories() {
        $db = & JFactory::getDBO();
        $query = "SELECT * FROM #__puntos_categorie ORDER BY " . puntosHelper::getSettings('category_ordering', 'id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

}