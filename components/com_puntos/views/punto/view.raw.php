<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');


class PuntosViewPunto extends PuntosView
{

	public function display($tpl = null)
	{
		$model = $this->getModel();
		$this->profile = '';
		$this->Punto = PuntosUtils::preparePunto($model->getPunto());

		if (PuntosHelper::getSettings('profile_link', ''))
		{
			$this->profile = PuntosHelperProfiles::getProfileLink($this->Punto->created_by, PuntosHelper::getSettings('profile_link', ''));
		}

		$this->settings = $this->prepareSettings();

		$this->setLayout('single');
		parent::display('raw');
	}



	private function prepareSettings()
	{
		$settings = new JObject;
		$properties = array(
			'show_address' => PuntosHelper::getSettings('show_address', 1),
			'show_country' => PuntosHelper::getSettings('show_address_country', 0),
			'show_author' => PuntosHelper::getSettings('show_author', 1),
			'show_date' => PuntosHelper::getSettings('show_date', 1),
			'show_detailpage' => PuntosHelper::getSettings('Punto_detailpage', 1)
		);

		$settings->setProperties($properties);

		return $settings;
	}
}
