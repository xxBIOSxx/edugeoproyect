<?php

defined('_JEXEC') or die('Restricted access');


class ModPuntosHelper
{
	
	public static function getList($params)
	{
		JLoader::register('PuntosModelPuntos', JPATH_ADMINISTRATOR . '/components/com_Puntos/models/Puntos.php');
		$model = new PuntosModelPuntos(array('ignore_request' => true));

		$model->setState('filter.published', 1);
		$model->setState('list.limit', $params->get('limit', 10));
		$model->setState('list.ordering', 'a.' . $params->get('ordering', 'created'));
		$model->setState('list.direction', $params->get('direction', 'desc'));
		$model->setState('filter.category_id', $params->get('catid', ''));

		$Puntos = $model->getItems();

		return $Puntos;
	}
}
