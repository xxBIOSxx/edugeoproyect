<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');


class PuntosControllerPuntos extends JControllerLegacy
{

	public function rss()
	{
		puntosUtils::createFeed();
	}
}
