<?php


defined('_JEXEC') or die();

class LiveUpdateStorageComponent extends LiveUpdateStorage
{
	private static $component = null;
	private static $key = null;

	public function load($config)
	{
		if(!array_key_exists('component', $config)) {
			self::$component = $config['extensionName'];
		} else {
			self::$component = $config['component'];
		}

		if(!array_key_exists('key', $config)) {
			self::$key = 'liveupdate';
		} else {
			self::$key = $config['key'];
		}

		
		$db = JFactory::getDbo();
		$sql = $db->getQuery(true)
			->select($db->qn('params'))
			->from($db->qn('#__extensions'))
			->where($db->qn('type').' = '.$db->q('component'))
			->where($db->qn('element').' = '.$db->q(self::$component));
		$db->setQuery($sql);
		$rawparams = $db->loadResult();
		$params = new JRegistry();
		$params->loadString($rawparams, 'JSON');

		$data = $params->get(self::$key, '');

		jimport('joomla.registry.registry');
		self::$registry = new JRegistry('update');

		self::$registry->loadString($data, 'INI');
	}

	public function save()
	{
		$data = self::$registry->toString('INI');

		$db = JFactory::getDBO();

	

		$sql = $db->getQuery(true)
			->select($db->qn('params'))
			->from($db->qn('#__extensions'))
			->where($db->qn('type').' = '.$db->q('component'))
			->where($db->qn('element').' = '.$db->q(self::$component));
		$db->setQuery($sql);
		$rawparams = $db->loadResult();
		$params = new JRegistry();
		$params->loadString($rawparams, 'JSON');

		$params->set(self::$key, $data);

	
		$data = $params->toString('JSON');
		$sql = $db->getQuery(true)
			->update($db->qn('#__extensions'))
			->set($db->qn('params').' = '.$db->q($data))
			->where($db->qn('type').' = '.$db->q('component'))
			->where($db->qn('element').' = '.$db->q(self::$component));

		$db->setQuery($sql);
		$db->query();
	}
}
