<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

class plgpuntosEmail extends JPlugin
{
	public function __construct(&$subject, $params)
	{

		$jlang = JFactory::getLanguage();
		$jlang->load('com_hotspots', JPATH_SITE, 'en-GB', true);
		$jlang->load('com_hotspots', JPATH_SITE, $jlang->getDefault(), true);
		$jlang->load('com_hotspots', JPATH_SITE, null, true);

		parent::__construct($subject, $params);
	}

	public function onAfterpuntoSave($context, $data)
	{

		$jform = JFactory::getApplication()->input->get('jform', array(), 'ARRAY');
		if (!$data->created_by) {
			$mail = $jform['email'];
		} else {
			$user = JFactory::getUser($data->created_by);
			$mail = $user->email;
			if(!$data->created_by_alias) {
				$data->created_by_alias = $user->name;
			}
		}
		$data->email = $mail;

		$this->sendMail($data);

		return true;
	}

	public function sendMail($row)
	{
		$mailList = $this->getModerators();

		if ($mailList) {
			if ($row->published) {
				$url = JURI::root() . JRoute::_('index.php?option=com_puntos&view=punto&id=' . $row->id);
			} else {
				$url = JURI::base();
			}
			$subject = JText::_('COM_PUNTOS_MAIL_SUBJECT') . ': ' . $row->name;
			$subject .= '[' . JText::_('COM_PUNTOS_MAIL_FROM') . ':' . $row->created_by_alias . "]";
			$message = '<p>' . JText::_('COM_PUNTOS_MAIL_A_NEW_MARKER') . ' ' . $url . ' :</p>';
			$message .= '<p><b>' . JText::_('COM_PUNTOS_MAIL_AUTHOR') . ': </b>' . $row->created_by_alias . ' (' . $row->created_by . ') <br />';
			$message .= '<b>' . JText::_('COM_PUNTOS_MAIL_AUTHOR_MAIL') . ': </b>' . $row->email . '<br />';
			$message .= '<b>' . JText::_('COM_PUNTOS_MAIL_PUNTO_TITLE') . ': </b>' . $row->name . '<br />';
			$message .= '<b>' . JText::_('COM_PUNTOS_MAIL_PUNTO_ADDRESS') . ': </b>' . $row->street . ' ' . $row->plz . ' ' . $row->town . '<br />';
			$message .= '<b>' . JText::_('COM_PUNTOS_MAIL_PUNTO_SHORT_DESCRIPTION') . ': </b>' . $row->description_small . '</p>';
			$message .= '<p>' . JText::_('COM_PUNTOS_MAIL_NOTICE') . '</p>';

			$mailer = JFactory::getMailer();

			foreach ($mailList as $mail) {
				$mailer->sendMail($mailer->From, $mailer->FromName, $mail, $subject, $message, true);
			}
		}
	}

	public function getModerators() {
		$db = JFactory::getDBO();
		$moderators = array();

		$moderatorGroups = $this->params->get('email_notification', array());
		if(count($moderatorGroups)) {
			$query = $db->getQuery(true);
			$query->select('DISTINCT u.email')
				->from('#__users AS u')
				->leftJoin('#__user_usergroup_map AS m ON u.id = m.user_id')
				->where('m.group_id IN (' . implode(',', $moderatorGroups) . ')');
			$db->setQuery($query);
			$moderators = $db->loadRowList();
		}

		return $moderators;
	}
}