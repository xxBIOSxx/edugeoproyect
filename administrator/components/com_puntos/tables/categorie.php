<?php


defined('_JEXEC') or die ('Restricted access');


class Tablecategorie extends JTable
{

	public function __construct(&$db)
	{
		parent::__construct('#__puntos_categorie', 'id', $db);
	}

	
	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params']))
		{
		
			if (isset($array['params']['tile_marker_color']))
			{
				$array['params']['tile_marker_color'] = puntosHelperColor::hex2rgb($array['params']['tile_marker_color']);
			}

			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}

	
	public function store($updateNulls = false)
	{
		$date = JFactory::getDate();

		if (!$this->id)
		{
		
			if (!(int) $this->cat_date)
			{
				$this->cat_date = $date->toSql();
			}
		}

		return parent::store($updateNulls);
	}
}
