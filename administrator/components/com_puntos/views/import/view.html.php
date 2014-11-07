<?php


defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.view');
class PuntosViewImport extends JViewLegacy
{
    public function display($tpl = null)
    {
        $this->addToolbar();
        parent::display($tpl);
    }

    private function addToolbar()
    {
        JToolBarHelper::title(JText::_('COM_PUNTOS_IMPORT'), 'generic.png');
        JToolBarHelper::help('screen.puntos', false, 'https://compojoom.com/support/documentation/puntos?tmpl=component');
    }
}