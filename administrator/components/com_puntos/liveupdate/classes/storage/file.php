<?php


defined('_JEXEC') or die();


class LiveUpdateStorageFile extends LiveUpdateStorage
{
	private static $filename = null;
	
	public function load($config)
	{
		$path = $config['path'];
		$extname = $config['extensionName'];
		$filename = "$path/$extname.updates.ini";
		
		self::$filename = $filename;
		
		jimport('joomla.registry.registry');
		self::$registry = new JRegistry('update');
		
		jimport('joomla.filesystem.file');
		if(JFile::exists(self::$filename)) {
			self::$registry->loadFile(self::$filename, 'INI');
		}
	}
	
	public function save()
	{
		jimport('joomla.filesystem.file');
		$data = self::$registry->toString('INI');
		JFile::write(self::$filename, $data);
	}
} 