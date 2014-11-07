<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controllerform');
require_once( JPATH_COMPONENT_ADMINISTRATOR . '/helpers/puntos.php');

class PuntosControllerKml extends JControllerForm {

    public function __construct() {
        parent::__construct();

        // Register Extra tasks
        $this->registerTask('add', 'edit');
    }

    public function getModel($name = 'Kml', $prefix = 'PuntosModel',$config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }


	
	public function save($key = null, $urlVar = null)
	{

	
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

	
		$app = JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$model = $this->getModel();
		$table = $model->getTable();
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		$checkin = property_exists($table, 'checked_out');
		$context = "$this->option.edit.$this->context";
		$task = $this->getTask();

	
		if (empty($key))
		{
			$key = $table->getKeyName();
		}

	
		if (empty($urlVar))
		{
			$urlVar = $key;
		}

		$recordId = JRequest::getInt($urlVar);

		if (!$this->checkEditId($context, $recordId))
		{
		.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $recordId));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
						. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}

	
		$data[$key] = $recordId;

	
		if ($task == 'save2copy')
		{
		
			if ($checkin && $model->checkin($data[$key]) === false)
			{
			
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
				$this->setMessage($this->getError(), 'error');

				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
							. $this->getRedirectToItemAppend($recordId, $urlVar), false
					)
				);

				return false;
			}

		
			$data[$key] = 0;
			$task = 'apply';
		}

	
		if (!$this->allowSave($data, $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
						. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}


		$form = $model->getForm($data, false);

		if (!$form)
		{
			$app->enqueueMessage($model->getError(), 'error');

			return false;
		}

		$file = JRequest::getVar('jform', '', 'files', 'array');

		if(!empty($file)) {
			if(!empty($file['name']['kml_file'])) {
				foreach($file as $key => $value) {
					$data['kml_file'] = $value['kml_file'];
				}
			}
		}


		$validData = $model->validate($form, $data);

		
		if ($validData === false)
		{
			
			$errors = $model->getErrors();

			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

	
			$app->setUserState($context . '.data', $data);

		
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $key), false
				)
			);

			return false;
		}


	
		if (!$model->save($validData))
		{

			$app->setUserState($context . '.data', $validData);

			
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $key), false
				)
			);

			return false;
		}


	
		if ($checkin && $model->checkin($validData[$key]) === false)
		{
			$app->setUserState($context . '.data', $validData);

		
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $key), false
				)
			);

			return false;
		}

		$this->setMessage(
			JText::_(
				($lang->hasKey($this->text_prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS')
					? $this->text_prefix
					: 'JLIB_APPLICATION') . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
			)
		);

		
		switch ($task)
		{
			case 'apply':
			
				$recordId = $model->getState($this->context . '.id');
				$this->holdEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				$model->checkout($recordId);

			
				$this->setRedirect(
					JRoute::_(
						'index.php?option=com_puntos&view=' . $this->view_item
							. '&layout=edit&puntos_kml_id='.$recordId, false
					)
				);
				break;

			case 'save2new':
		
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);

			
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
							. $this->getRedirectToItemAppend(null, $key), false
					)
				);
				break;

			default:
	
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);

				
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_list
							. $this->getRedirectToListAppend(), false
					)
				);
				break;
		}


		$this->postSaveHook($model, $validData);

		return true;
	}

    public function cancel($key=null) {
        $link = 'index.php?option=com_puntos&view=kmls';
        $this->setRedirect($link);
    }

}