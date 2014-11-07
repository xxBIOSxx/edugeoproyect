<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class PuntosViewPunto extends PuntosView
{

	public function display($tpl = null)
	{
		$this->form = $this->get('Form');
		$this->punto = $this->get('Item');

		$this->canDo = PuntosHelper::getActions($this->punto->id);

		$this->addToolbar();
		parent::display($tpl);
	}

	public function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_PUNTOS_EDIT_MARKER'));
		JToolBarHelper::save('punto.save');
		JToolBarHelper::apply('punto.apply');
		JToolBarHelper::cancel('punto.cancel');
	}
}