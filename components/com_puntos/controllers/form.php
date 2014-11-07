<?php



defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controllerform');

class PuntosControllerForm extends JControllerForm
{
	public $context = 'punto';

	public $view_item = 'form';
	public $view_list = 'puntos';

	public function edit($key = null, $urlVar = 'id')
	{
		$result = parent::edit($key, $urlVar);

		return $result;
	}


	protected function allowEdit($data = array(), $key = 'id')
	{
	
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;

		$user = JFactory::getUser();
		$userId = $user->get('id');
		$asset = 'com_puntos.marker.' . $recordId;

	
		if ($user->authorise('core.edit', $asset))
		{
			return true;
		}

	
		if ($user->authorise('core.edit.own', $asset))
		{
		
			$ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;

			if (empty($ownerId) && $recordId)
			{
				
				$record = $this->getModel()->getItem($recordId);

				if (empty($record))
				{
					return false;
				}

				$ownerId = $record->created_by;
			}

		
			if ($ownerId == $userId)
			{
				return true;
			}
		}

		
		return parent::allowEdit($data, $key);
	}


	protected function getRedirectToListAppend()
	{
		$append = '&layout=userpuntos';

		return $append;
	}
}