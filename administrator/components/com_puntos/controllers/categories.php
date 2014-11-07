<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class puntosControllercategories extends puntosController {

	private $blacklist = array( ".php", 
								".phtml", 
								".php3", 
								".php4", 
								".php5", 
								".html", 
								".txt", 
								".dhtml", 
								".htm", 
								".doc", 
								".asp", 
								".net", 
								".js", 
								".rtf"
		);

    public function __construct() {
        parent::__construct();

 
        $this->registerTask('unpublish', 'publish');
    }

    public function display() {
        $document = & JFactory::getDocument();
        $viewName = JRequest::getVar('view', 'categories');
        $viewType = $document->getType();
        $view = &$this->getView($viewName, $viewType);
        $model = $this->getModel('Category', 'PuntosModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    public function remove() {
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $db = & JFactory::getDBO();
        $msg = JText::_('COM_PUNTOS_REMOVE_CATEGORIES_FAILED');
        if (count($cid)) {
            $cids = implode(',', $cid);
            $query = "DELETE FROM #__puntos_categorie where id IN ( $cids )";
            $db->setQuery($query);
            if (!$db->query()) {
                echo "<script> alert('" . $db->getErrorMsg() . "'); window.history.go (-1); </script>\n";
            } else {
                $msg = JText::_('COM_PUNTOS_REMOVE_CATEGORIES_SUCCESS');
            }
        }
        $this->setRedirect('index.php?option=com_puntos&view=categories', $msg);
    }


    public function publish() {
        $cid = JRequest::getVar('cid', array(), '', 'array');

		if ($this->task == 'publish') {
            $publish = 1;
        } else {
            $publish = 0;
        }

        $msg = "";
        $puntoTable = & JTable::getInstance('categorie', 'Table');
        $puntoTable->publish($cid, $publish);

        $link = 'index.php?option=com_puntos&view=categories';

        $this->setRedirect($link, $msg);
    }

}