<?php


defined('_JEXEC') or die();

jimport('joomla.application.component.view');
jimport('joomla.application.module.helper');

class ControlCenterView extends JViewLegacy
{
	public function display($tpl = null)
	{
        $config = ControlCenterConfig::getInstance();

        JToolBarHelper::title(JText::_($config->_extensionTitle).' &ndash; '.JText::_('COMPOJOOM_CONTROLCENTER_TASK_OVERVIEW'),'controlcenter');
        JToolBarHelper::help('screen.' . $config->_extensionTitle);

        $this->assign('config', $config);

        switch(JRequest::getCmd('task','overview'))
        {
            case 'information':
                $this->setLayout('information');
                break;

            case 'overview':
            default:
                $this->setLayout('overview');
                break;
        }

        parent::display($tpl);
    }

}