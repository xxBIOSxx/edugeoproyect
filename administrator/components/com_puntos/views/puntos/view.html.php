<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class PuntosViewPuntos extends JViewLegacy
{

	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$appl = JFactory::getApplication();
		$db = JFactory::getDBO();


		$context = 'com_puntos.marker.list.';
		$filter_sectionid = $appl->getUserStateFromRequest($context . 'filter_category_id', 'filter_category_id', 0, 'int');
		$filter_order = $appl->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'cc.catid', 'cmd');
		$filter_order_Dir = $appl->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');

		$this->list = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;


		$query = 'SELECT s.cat_name AS text, s.id AS value'
			. ' FROM #__puntos_categorie AS s'
			. ' ORDER BY s.id';
		$db->setQuery($query);
		$puntossets = $db->loadObjectList();

		array_unshift($puntossets, JHTML::_('select.option', '', '- ' . JText::_('COM_PUNTOS_SELECT_CATEGORY') . ' -', 'value', 'text'));
		$lists['sectionid'] = JHTML::_('select.genericlist', $hotspotssets, 'filter_category_id', array('onchange' => 'this.form.submit();'), 'value', 'text', $filter_sectionid);

		$ordering = ($lists['order'] == 'cc.catid'); 

		$this->lists = $lists;
		$this->ordering = $ordering;

		if (JRequest::getVar('layout') == 'element')
		{
			$this->setLayout('element');
		}
		$this->addToolbar();
		parent::display($tpl);
	}

	public function addToolbar()
	{
		$canDo = PuntosHelper::getActions();

		JToolBarHelper::title(JText::_('COM_PUNTOS_MARKERS'), 'generic.png');
		JToolBarHelper::custom('geocode', 'geocoding', 'geocoding', 'COM_PUNTOS_GEOCODE');
		if ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::publishList('puntos.publish');
			JToolBarHelper::unpublishList('puntos.unpublish');
		}
		JToolBarHelper::deleteList(JText::_('COM_PUNTOS_DO_YOU_REALLY_WANTO_TO_REMOVE_THIS_MARKER'), 'puntos.remove');
		if ($canDo->get('core.edit'))
		{
			JToolBarHelper::editList('punto.edit');
		}

		if (PUNTOS_PRO || (!PUNTOS_PRO && $this->pagination->total <= 100))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('punto.add');
			}
		}

	}
}
