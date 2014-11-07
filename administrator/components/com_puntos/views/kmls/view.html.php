<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class PuntosViewKmls extends JViewLegacy {

	public function display($tpl = null) {
        $appl = JFactory::getApplication();
        $this->kmls = $this->get('Items');
        $this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');

        $context = 'com_puntos.kmls.list.';
        $filter_order = $appl->getUserStateFromRequest($context . 'filter_order', 'filter_order', '', 'cmd');
        $filter_order_Dir = $appl->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');

        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        $this->lists = $lists;

        $this->addToolbar();
		parent::display($tpl);
	}

    public function addToolbar() {
        JToolBarHelper::title(JText::_('COM_PUNTOS_KML'), 'kmls');
        JToolBarHelper::publishList('kmls.publish');
        JToolBarHelper::unpublishList('kmls.unpublish');
        JToolBarHelper::deleteList(JText::_('COM_PUNTOS_DO_YOU_REALLY_WANTO_TO_REMOVE_THIS_KML_FILE'),'kmls.remove');
        JToolBarHelper::editList('kml.edit');
        JToolBarHelper::addNew('kml.add');

    }

}