<?php


defined('_JEXEC') or die('Restricted access');

class PuntosViewPuntos extends PuntosView
{

	public function display($tpl = null)
	{
		if (JRequest::getCmd('layout') == 'userpuntos')
		{
			$this->setLayout('userpuntos');
			$this->userpuntos();
			return;
		}

		$this->cats = puntosUtils::get_front_categories();

		$this->_prepareDocument();
		parent::display($tpl);
	}

	public function userpuntos($tpl = null)
	{
		$this->user = JFactory::getUser();
		$model = JModelLegacy::getInstance('punto', 'puntosModel');
		$puntos = $model->getUserpuntos($this->user->id);

		foreach ($puntos as $key => $punto)
		{
			$urlcat = $punto->catid . ':' . JFilterOutput::stringURLSafe($punto->cat_name);
			$urlid = $punto->id . ':' . JFilterOutput::stringURLSafe($punto->name);
			$puntos[$key]->link = JRoute::_(puntosHelperRoute::getpuntoRoute($urlid, $urlcat));
		}
		$this->puntos = $puntos;
		parent::display($tpl);
	}

	protected function _prepareDocument()
	{
		$menu = JFactory::getApplication()->getMenu()->getActive();
		$params = $menu->params;

		if ($params->get('menu-meta_description'))
		{
			$this->document->setDescription($params->get('menu-meta_description'));
		}

		if ($params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $params->get('menu-meta_keywords'));
		}

		if ($params->get('robots'))
		{
			$this->document->setMetadata('robots', $params->get('robots'));
		}
	}

}