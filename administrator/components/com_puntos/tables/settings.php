<?php

defined( '_JEXEC' ) or die ( 'Restricted access' );

jimport('joomla.filter.input');

class TableSettings extends JTable
{
	var $id 				= null;
	var $title 				= null;
	var $value				= null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__puntos_settings', 'id', $db );
	}
}
?>