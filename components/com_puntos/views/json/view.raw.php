<?php


defined('_JEXEC') or die ('Restricted access');
require_once JPATH_COMPONENT_SITE . '/views/json.php';


class PuntosViewJson extends PuntosJson
{
	
	public function display($tpl = null)
	{
		$model = $this->getModel();
		$list = ($model->getPuntos());

		if (!isset($list['Puntos']))
		{
			$list['Puntos'] = array();
		}

		foreach ($list['Puntos'] as $catid => $Punto)
		{
			foreach ($Punto as $key => $value)
			{
				if ($key !== 'viewCount' && $key !== 'categoryCount')
				{
					$list['Puntos'][$catid][$key] = PuntosUtils::preparePunto($value);
				}
			}
		}

		$this->list = $list;
		$this->settings = $this->prepareSettings();

		parent::display($tpl);
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

	
	public function search(array $list)
	{
		$cat = JRequest::getVar('cat', false);
		$level = JRequest::getVar('level', false);
		$position = JRequest::getVar('position', false);

		$settings = $this->prepareSettings();

		foreach ($list['Puntos'] as $key => $Punto)
		{
			$list['Puntos'][$key] = PuntosUtils::preparePunto($Punto);
		}

		$this->list = $list;
		$this->settings = $settings;
		$this->categoryid = $cat;
		$this->hsposition = $position;
		$this->hslevel = $level;

		parent::display(null);
	}
}
