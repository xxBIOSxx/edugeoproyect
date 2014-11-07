<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
jimport('joomla.filesystem');

class PuntosViewKML extends PuntosView {

	public function display($tpl = null) {
        $this->form = $this->get('Form');
		$this->item = $this->get('Item');


        $this->addToolbar();
		parent::display($tpl);
	}

    public function addToolbar() {
        JToolBarHelper::title(JText::_('COM_PUNTOS_EDIT_KML'), 'kml');
        JToolBarHelper::save('kml.save');
        JToolBarHelper::apply('kml.apply');
        JToolBarHelper::cancel('kml.cancel');
    }
}