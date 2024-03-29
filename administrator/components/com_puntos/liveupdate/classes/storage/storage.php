<?php


defined('_JEXEC') or die();

class LiveUpdateStorage
{

	public static $registry = null;


	public static function getInstance($type, $config)
	{
		static $instances = array();

		$sig = md5($type, serialize($config));
		if(!array_key_exists($sig, $instances)) {
			require_once dirname(__FILE__).'/'.strtolower($type).'.php';
			$className = 'LiveUpdateStorage'.ucfirst($type);
			$object = new $className($config);
			$object->load($config);
			$newRegistry = clone(self::$registry);
			$object->setRegistry($newRegistry);
			$instances[$sig] = $object;
		}
		return $instances[$sig];
	}


	public function &getRegistry()
	{
		return self::$registry;
	}


	public function setRegistry($registry)
	{
		self::$registry = $registry;
	}


	public final function set($key, $value)
	{
		if($key == 'updatedata') {
			if(function_exists('json_encode') && function_exists('json_decode')) {
				$value = json_encode($value);
			} elseif(function_exists('base64_encode') && function_exists('base64_decode')) {
				$value = base64_encode(serialize($value));
			} else {
				$value = serialize($value);
			}
		}
		self::$registry->set("update.$key", $value);
	}

	public final function get($key, $default)
	{
		$value = self::$registry->get("update.$key", $default);
		if($key == 'updatedata') {
			if(function_exists('json_encode') && function_exists('json_decode')) {
				$value = json_decode($value);
			} elseif(function_exists('base64_encode') && function_exists('base64_decode')) {
				$value = unserialize(base64_decode($value));
			} else {
				$value = unserialize($value);
			}
		}
		return $value;
	}

	public function save() {}

	public function load($config) {}
}
