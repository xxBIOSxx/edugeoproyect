<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
require_once( JPATH_COMPONENT_ADMINISTRATOR . '/libraries/recaptcha/puntosRecaptcha.php');
class puntosViewForm extends HotspotsView {

	public function display($tpl = null) {
	
		$user		= JFactory::getUser();
		$this->recaptcha = null;

	
		$this->state		= $this->get('State');
		$this->item			= $this->get('Item');
		$this->form			= $this->get('Form');

		$this->returnPage	= $this->get('ReturnPage');

		if (empty($this->item->id)) {
			$authorised = $user->authorise('core.create', 'com_puntos');
		} else {
			$authorised = $user->authorise('core.edit.own', 'com_puntos');
		}


		if ($authorised !== true) {
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}


		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		$params	= &$this->state->params;

		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->params	= $params;
		$this->user		= $user;

		$userRecaptcha = puntosUtils::isUserInGroups(puntosHelper::getSettings('captcha_usergroup', array()));
		if (puntosHelper::getSettings('captcha', 1) && $userRecaptcha ) {
			$recaptcha = new puntosRecaptcha();

			$this->recaptcha = $recaptcha->getHtml();
		}

		$this->setLayout('form');
		parent::display($tpl);
	}
	
	private function prepareSettings() {
		$settings = new JObject();
		$properties = array (
			'show_address' => puntosHelper::getSettings('show_address', 1),
			'show_country' => puntosHelper::getSettings('show_address_country', 0),
			'show_author' => puntosHelper::getSettings('show_author', 1),
			'show_date' => puntosHelper::getSettings('show_date', 1),
			'show_detailpage' => puntosHelper::getSettings('punto_detailpage', 1)
		);
		
		$settings->setProperties($properties);
		
		return $settings;
	}

	
	private function preparepunto($punto) {

		$description = $punto->description;
		
		if (puntosHelper::getSettings('marker_allow_plugin', 0) == 1) {
			$description = JHTML::_('content.prepare', $description, '');
		}
		$punto->postdate = puntosUtils::getLocalDate($punto->postdate);
		if($punto->picture_thumb) {
			$punto->picture_thumb = PUNTOS_THUMB_PATH . $punto->picture_thumb;
		}
		if($punto->picture) {
			$punto->picture = PUNTOS_PICTURE_PATH . $punto->picture;
		}

		$punto->description = $description;
			
		return $punto;
	}
}