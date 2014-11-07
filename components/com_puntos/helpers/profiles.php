<?php


defined('_JEXEC') or die('Restricted access');

class PuntosHelperProfiles
{

	public static function getProfileLink($userId, $type)
	{
		$link = '';

		if (!$userId)
		{
			return $link;
		}

		if ($type == 'CB')
		{
			$link = self::getCB($userId);
		}

		if ($type == 'jomsocial')
		{
			$link = self::getJomsocial($userId);
		}

		return $link;
	}


	private static function getCB($userId)
	{
		$itemId = '';

		if (PuntosUtils::getItemid('com_comprofiler'))
		{
			$itemId = '&Itemid=' . PuntosUtils::getItemid('com_comprofiler');
		}

		$link = JRoute::_('index.php?option=com_comprofiler&task=userProfile&user=' . $userId . $itemId);

		return $link;
	}


	private static function getJomsocial($userId)
	{
		$jspath = JPATH_ROOT . '/components/com_community';
		include_once $jspath . '/libraries/core.php';

		$link = CRoute::_('index.php?option=com_community&view=profile&userid=' . $userId);

		return $link;
	}
}
