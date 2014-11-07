<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class PuntosJson extends PuntosView {
	
	
	public function __construct() {
		parent::__construct();
		
		$document = JFactory::getDocument();
		$document->setMimeEncoding( 'application/json' );

		
	}
}

