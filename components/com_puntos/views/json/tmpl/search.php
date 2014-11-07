<?php


defined('_JEXEC') or die ('Restricted access');
header('Content-type: application/json');

$html = array();

foreach ($this->list['puntos'] as $key => $value)
{
	$this->punto = $value;

	$html['puntos'][$value->catid][$key] = array(
		'id' => $value->puntos_id,
		'latitude' => $value->gmlat,
		'longitude' => $value->gmlng,
		'title' => $value->name,
		'description' => preg_replace("@[\\r|\\n|\\t]+@", '', $this->punto->description_small),
		'street' => $this->punto->street,
		'city' => $this->punto->town,
		'zip' => $this->punto->plz,
		'country' => $this->punto->country,
		'date' => $this->punto->created,
		'readmore' => $this->punto->link
	);

	if (puntosHelper::getSettings('show_author', 1))
	{
		$html['puntos'][$value->catid][$key]['created_by'] = $this->punto->created_by_alias ? $this->punto->created_by_alias : $this->punto->user_name;
	}

	if ($value->params->get('markerimage'))
	{
		$html['puntos'][$value->catid][$key]['icon'] = PUNTOS_PICTURE_CATEGORIES_PATH . $value->params->get('markerimage');
	}
}

$html['worldCount'] = $this->list['worldCount'];
$html['viewCount'] = $this->list['count'];
$html['offset'] = JRequest::getInt('offset');
echo json_encode($html);

jexit();