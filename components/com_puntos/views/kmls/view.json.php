<?php

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_SITE .'/views/json.php');
class PuntosViewKmls extends PuntosJson
{

	public function display($tpl = null)
	{
		$this->kmls = $this->get('Kmls');
		parent::display();
	}
}