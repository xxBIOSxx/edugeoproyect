<?php


defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.view');


class PuntosViewMail extends JViewLegacy
{
	
	public function display($tpl = null)
	{
		$user = JFactory::getUser();

		$imageSrc = $this->getStaticMap();

		$this->imageSrc = $imageSrc;
		$this->name = $user->get('name');
		$this->email = $user->get('email');

		parent::display($tpl);
	}


	public function getStaticMap()
	{
		$input = JFactory::getApplication()->input;
		$staticUrl = "http://maps.google.com/maps/api/staticmap?";

		$url[] = $input->getInt('zoom') ? 'zoom=' . $input->getInt('zoom') : 'zoom=1';
		$url[] = $input->getString('center') ? 'center=' . $input->getString('center') : '';

		$url[] = $input->getString('markers') ? 'markers=' . $input->getString('markers') : '';
		$url[] = $input->getString('sensor') ? 'sensor=' . $input->getString('sensor') : 'sensor=false';
		$url[] = $input->getString('path') ? 'path=' . $input->getString('path') : '';
		$url[] = $input->getString('maptype') ? 'maptype=' . $input->getString('maptype') : '';
		$url[] = 'size=' . PuntosHelper::getSettings('map_static_width', 500)
			. 'x' . PuntosHelper::getSettings('map_static_height', 300);

		return $staticUrl . htmlentities(implode('&', $url), ENT_QUOTES, 'UTF-8');
	}
}
