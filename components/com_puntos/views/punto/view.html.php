<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class PuntosViewPunto extends PuntosView
{

	public function display($tpl = null)
	{
		$this->profile = '';
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$model = $this->getModel();
		$user = JFactory::getUser();
		$punto = $this->preparePunto($model->getPunto());
		$categoryModel = JModelLegacy::getInstance('category', 'puntosModel');
		$category = puntosUtils::prepareCategory($categoryModel->getCategory($punto->catid));

		$settings = $this->prepareSettings();
		$pathway = $mainframe->getPathWay();
		$pathway->additem($punto->name, '');

		$hsid = $input->getInt('id', 0);

		$itemId = '&Itemid=' . puntosUtils::getItemid('com_puntos', 'puntos');

		$backlink = JRoute::_('index.php?option=com_puntos' . $itemId);

		$this->hotid = $hsid;
		$this->punto = $punto;
		$this->category = $category;
		$this->settings = $settings;
		$this->backlink = $backlink;
		$this->name = $user->name;

		if (puntosHelper::getSettings('profile_link', ''))
		{
			$this->profile = puntosHelperProfiles::getProfileLink($this->punto->created_by, puntosHelper::getSettings('profile_link', ''));
		}

		parent::display($tpl);
	}


	private function prepareSettings()
	{
		$settings = new JObject();
		$properties = array(
			'show_address' => puntosHelper::getSettings('show_address', 1),
			'show_country' => puntosHelper::getSettings('show_address_country', 0),
			'show_author' => puntosHelper::getSettings('show_author', 1),
			'show_date' => puntosHelper::getSettings('show_date', 1),
			'show_detailpage' => puntosHelper::getSettings('punto_detailpage', 1)
		);

		$settings->setProperties($properties);

		return $settings;
	}


	private function preparepunto($punto)
	{
		if (puntosHelper::getSettings('marker_allow_plugin', 0) == 1)
		{
			$punto->description_small = JHTML::_('content.prepare', $punto->description_small, '');
			$punto->description = JHTML::_('content.prepare', $punto->description, '');
		}
		$punto->created = puntosUtils::getLocalDate($punto->created);
		if ($punto->picture_thumb)
		{
			$punto->picture_thumb = puntoS_THUMB_PATH . $punto->picture_thumb;
		}
		if ($punto->picture)
		{
			$punto->picture = puntoS_PICTURE_PATH . $punto->picture;
		}


		return $punto;
	}
}