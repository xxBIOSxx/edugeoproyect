<?php


defined('_JEXEC') or die();


class LiveUpdateConfig extends LiveUpdateAbstractConfig
{
	var $_extensionName = 'com_puntos';
	var $_versionStrategy = 'different';


	public function __construct()
	{
		$this->_extensionTitle = 'CComment ' . (PUNTOS_PRO == 1 ? 'Professional' : 'Core');
		$this->_requiresAuthorization = (PUNTOS_PRO == 1);
		$this->_currentVersion = PUNTOS_VERSION;
		$this->_currentReleaseDate = PUNTOS_DATE;

		if (PUNTOS_PRO)
		{
			$this->_updateURL = 'http://compojoom.com/index.php?option=com_ars&view=update&format=ini&id=4';
		}
		else
		{
			$this->_updateURL = 'https://compojoom.com/index.php?option=com_ars&view=update&format=ini&id=18';
		}

		
		$this->_downloadID = JComponentHelper::getParams('com_puntos')->get('global.downloadid');

		parent::__construct();
	}
}
