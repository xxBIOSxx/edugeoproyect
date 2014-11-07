<?php


defined('_JEXEC') or die('Restricted access');

class plgpuntoslinksExternal extends JPlugin
{
	public function __construct($subject, $params = array())
	{
		parent::__construct($subject, $params);

		$this->loadLanguage('plg_puntoslinks_external.sys');
	}


	public function onCreateLink($id)
	{
		
		return JRoute::_($id);
	}
}