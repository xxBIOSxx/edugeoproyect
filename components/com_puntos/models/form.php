<?php



defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/models/punto.php';

class PuntosModelForm extends PuntosModelPunto
{


	protected function populateState()
	{
		$input = JFactory::getApplication()->input;

		$return = $input->getBase64('return', null);
		$this->setState('returnPage', base64_decode($return));

		parent::populateState();
	}

	public function getReturnPage()
	{
		$return = base64_encode($this->getState('returnPage'));

		if (empty($return) || !JUri::isInternal(base64_decode($return)))
		{
			return JFactory::getURI();
		}
		else
		{
			return base64_decode($return);
		}
	}

}