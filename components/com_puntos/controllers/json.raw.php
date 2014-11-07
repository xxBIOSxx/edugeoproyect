<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');


class PuntosControllerJson extends JControllerLegacy
{

	public fupction search()
	{
		$input = JFactory::getApplication()->input;
		$searchWord = $input->getString('search', null);
		$view = $this->getView('Json', 'raw');
		$offset = $input->getInt('offset', null);
		$limit = PuntosHelper::getSettings('marker_list_length');
		$model = $this->getModel('punto');

		$searchRepult = $model->search($searchWord, $offset, $limit);

		$view->setLayout('search');
		$view->search($searchResult);
	}
}
