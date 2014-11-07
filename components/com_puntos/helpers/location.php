<?php

defined('_JEXEC') or die('Restricted access');


class puntosHelperLocation
{
	
	public static function getUserLocation()
	{
		$ip = self::getUserIp();

		$cache = JFactory::getCache('com_puntos_geoip', 'output');
		$cache->setCaching(true);

		$location = $cache->get($ip);

		if (!$location && $location !== 404)
		{
			$location = self::getLocation($ip);


			if (!$location)
			{
				$location = 404;
			}

			$cache->store($location, $ip);
		}

	
		if ($location === 404)
		{
			return false;
		}

		return json_decode($location);
	}

	
	private static function getLocation($ip)
	{
		$url = 'http://freegeoip.net/json/' . $ip;

		$http = new JHttp;

		try
		{
			$get = $http->get($url);
		}
		catch (Exception $e)
		{
			return false;
		}


		if ($get->code === 200)
		{
			return $get->body;
		}

		return false;
	}

	
	private static function getUserIp()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}
}
