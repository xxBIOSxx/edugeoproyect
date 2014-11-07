<?php



defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controllerform');

require_once(JPATH_COMPONENT_ADMINISTRATOR . '/libraries/recaptcha/puntosRecaptcha.php');

class puntosControllerpunto extends JControllerForm
{

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


	public function save($key = null, $urlVar = null)
	{
	
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		
		$app = JFactory::getApplication();
		$input = $app->input;
		$lang = JFactory::getLanguage();
		$model = $this->getModel();
		$table = $model->getTable();
		$user = JFactory::getUser();
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		$context = "$this->option.edit.$this->context";

		$itemId = '&Itemid=' . puntosUtils::getItemid('com_puntos', 'puntos');

		
		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		
		if (empty($urlVar))
		{
			$urlVar = $key;
		}

		$recordId = JRequest::getInt($urlVar);

		
		if (!$recordId && puntosHelper::getSettings('captcha', 1))
		{
			$userRecaptcha = puntosUtils::isUserInGroups(puntosHelper::getSettings('captcha_usergroup', array()));

			if ($userRecaptcha)
			{
				$recaptcha = new puntosRecaptcha;
				$challengeField = JRequest::getVar('recaptcha_challenge_field');
				$responseField = JRequest::getVar('recaptcha_response_field');
				$answer = $recaptcha->checkAnswer($challengeField, $responseField);

				if (!$answer->is_valid)
				{
					$app->setUserState($context . '.data', $data);
					$this->setRedirect($this->getReturnPage(), JText::_('COM_PUNTOS_INVALID_CAPTCHA'));

					return false;
				}
			}
		}


		if (!$this->checkEditId($context, $recordId))
		{
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


		if (!$this->allowSave($data, $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');


			$this->setRedirect(
				JRoute::_(
					'index.php?option=com_puntos&view=puntos' . $itemId
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


		if ($user->get('id'))
		{
			$data['email'] = $user->get('email');
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
					'index.php?option=com_puntos&view=form'
					. $this->getRedirectToItemAppend($recordId, $key), false
				)
			);

			return false;
		}

		if (!$user->authorise('core.edit.state', 'com_puntos'))
		{
			$validData['published'] = puntosHelper::getSettings('addhs_autopublish', 1) ? 1 : 0;
		}

	
		if (!$model->save($validData))
		{
			
			$app->setUserState($context . '.data', $validData);

		
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=com_puntos&view=form'
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


		$this->releaseEditId($context, $recordId);
		$app->setUserState($context . '.data', null);

		if (puntosHelper::getSettings('addhs_autopublish', 1))
		{
			$cats = puntosUtils::get_front_categories();

			if (isset($cats[$validData['catid']]))
			{
				$urlcat = $validData['catid'] . ':' . JFilterOutput::stringURLSafe($cats[$validData['catid']]['text']);
			}

			$urlid = $model->getState('punto.id') . ':' . JFilterOutput::stringURLSafe($validData['name']);
			$redirect = puntosHelperRoute::getpuntoRoute($urlid, $urlcat);
		}
		else
		{
			$redirect = 'index.php?option=com_puntos&view=puntos' . $itemId;
		}

		if ($validData['published'])
		{
		
			$this->setRedirect(JRoute::_($redirect, false), JText::_('COM_PUNTOS_punto_' . ($data['id'] ? 'EDITED' : 'ADDED')));
		}
		else
		{
		
			$this->setRedirect(
				JRoute::_('index.php?option=com_puntos&view=puntos' . $itemId, false),
				JText::sprintf('COM_PUNTOS_N_ITEMS_UNPUBLISHED', 1)
			);
		}

	
		$this->postSaveHook($model, $validData);

		return true;
	}



	public function getReturnPage()
	{
		$input = JFactory::getApplication()->input;
		$return = base64_encode($input->getBase64('returnPage', null));

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