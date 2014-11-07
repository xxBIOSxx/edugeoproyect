<?php


defined('_JEXEC') or die('Restricted access');


class PuntosHelper
{
	private static $instance;


	public static function getSettings($title = '', $default = '')
	{
		if (!isset(self::$instance))
		{
			self::$instance = self::_loadSettings();
		}

		return self::$instance->get($title, $default);
	}


	private static function _loadSettings()
	{
		$params = JComponentHelper::getParams('com_puntos');


		$app = JFactory::getApplication();
		$menu = $app->getMenu();

		if (is_object($menu))
		{
			if ($item = $menu->getActive())
			{
				$menuParams = $menu->getParams($item->id);

				foreach ($menuParams->toArray() as $key => $value)
				{
					if ($key == 'show_page_heading')
					{
						$key = 'show_page_title';
					}

					
					if ($key == 'styled_maps')
					{
						if (trim($value) == '')
						{
							continue;
						}
					}

					$params->set($key, $value);
				}
			}
		}

		return $params;
	}


	public static function getActions($messageId = 0, $unit = 'component', $assetName = 'com_puntos')
	{
		jimport('joomla.access.access');
		$user = JFactory::getUser();
		$result = new JObject;

		if (empty($messageId))
		{
			$asset = $assetName;
		}
		else
		{
			$asset = $assetName . '.' . $unit . '.' . (int) $messageId;
		}

		$actions = JAccess::getActions($assetName, $unit);

		foreach ($actions as $action)
		{
			$result->set($action->name, $user->authorise($action->name, $asset));
		}

		return $result;
	}
}
