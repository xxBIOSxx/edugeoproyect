<?php


defined('_JEXEC') or die ('Restricted access');

$html = array();

foreach ($this->list['puntos'] as $catid => $puntos)
{
	$catid = (int) $catid;

	foreach ($puntos as $key => $value)
	{
		if ($key !== 'categoryCount' && $key !== 'viewCount')
		{
			$this->punto = $value;
			$key = (int) $key;

			$html['puntos'][$catid][$key] = array(
				'id' => (int) $value->puntos_id,
				'latitude' => (float) $value->gmlat,
				'longitude' => (float) $value->gmlng,
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
				$html['puntos'][$catid][$key]['created_by'] = $this->punto->created_by_alias ? $this->punto->created_by_alias : $this->punto->user_name;
			}

			if (puntosHelper::getSettings('show_date', 1))
			{
				$html['puntos'][$catid][$key]['date'] = puntosUtils::getLocalDate($this->punto->created);
			}

			if ($value->picture_thumb)
			{
				$html['puntos'][$catid][$key]['thumb'] = $value->picture_thumb;
			}

			if ($value->params->get('markerimage'))
			{
				$html['puntos'][$catid][$key]['icon'] = puntoS_PICTURE_CATEGORIES_PATH . $value->params->get('markerimage');
			}
		}
		else
		{
			if ($key == 'categoryCount')
			{
				$html['puntos'][$catid]['categoryCount'] = (int) $value;
			}

			if ($key == 'viewCount')
			{
				$html['puntos'][$catid]['viewCount'] = (int) $value;
			}
		}
	}
}

$html['offset'] = JRequest::getInt('offset');

echo json_encode($html);

jexit();