<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class PuntosViewcategories extends JViewLegacy {

	function display($tpl = null) {
		$appl = JFactory::getApplication();
		$uri = JFactory::getURI();
		$model = $this->getModel();


	
		$context = 'com_puntos.categories.list.';
		$filter_state2 = $appl->getUserStateFromRequest($context . 'filter_state', 'filter_state', '', 'word');
		$filter_order2 = $appl->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'cc.cat_name', 'cmd');
		$filter_order_Dir2 = $appl->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');
		$search = $appl->getUserStateFromRequest($context . 'search', 'search', '', 'string');
		$search = JString::strtolower($search);

		$list = $model->getList();
		$pagination2 = $this->get('Pagination');
		$total2 = $this->get('Total');

		
		$filter['state'] = JHTML::_('grid.state', $filter_state2);

		
		$filter['order_Dir'] = $filter_order_Dir2;
		$filter['order'] = $filter_order2;

		$filter['search'] = $search;


		$this->list = $list;
		$this->filter = $filter;
		$this->pagination = $pagination2;
		$this->total = $total2;
		$this->request_url = $uri->toString();
		$this->user = JFactory::getUser();

        $this->addToolbar();
		parent::display($tpl);
	}

    public function addToolbar() {
       
        JToolBarHelper::title(JText::_('COM_PUNTOS_CATEGORIES'), 'categories');
        JToolBarHelper::publishList('categories.publish');
        JToolBarHelper::unpublishList('categories.unpublish');
        JToolBarHelper::deleteList(JText::_('COM_PUNTOS_DO_YOU_REALLY_WANTO_TO_REMOVE_THIS_CATEGORY'), 'categories.remove');
        JToolBarHelper::editList('category.edit');
        JToolBarHelper::addNew('category.add');

    }

}