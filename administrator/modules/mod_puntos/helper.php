<?php



defined('_JEXEC') or die;

class modPuntossHelper
{
	public static function getPuntos() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('m.id AS puntos m.gmlat as latitude,
					m.gmlng as longitude, m.name as title, m.*')
			->from('#__Puntosker as m')
			->leftJoin('#__puntosegorie AS c ON m.catid = c.id')
			->order('m.created DESC');
		$db->setQuery($query, 0, 25);

		return $db->loadObjectList();
	}

	public static function preparepuntosntos
		$json = array();

		foreach($puntos as $punto) {
			$json['puntos'][$punto->catid][$punto->id] = puntosUtils::preparepunto($punto);
		}

		return json_encode($json);
	}
}