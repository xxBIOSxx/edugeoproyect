<?php



defined('_JEXEC') or die('Restricted access');

class puntosHelperRoute {

  
    public static function getpuntoRoute($id, $catid = 0) {

		$needles = array(
			'punto' => (int) $id,
			'category' => (int) $catid,
		);

		$link = 'index.php?option=com_puntos&view=punto&catid='.$catid.'&id=' . $id;

		if ($item = puntosHelperRoute::_findItem($needles)) {
			$link .= '&Itemid=' . $item->id;
		}
		return $link;
	}


    private static function _findItem($needles) {
		$component = JComponentHelper::getComponent('com_puntos');
		$application = JFactory::getApplication();
		$menus = $application->getMenu('site', array());
		$items = $menus->getItems('component_id', $component->id);
		
		$match = null;

        if (count($items)) {
            // try to find a match
            foreach ($items as $item) {
                if ((@$item->query['id'] == $needles['punto'])) {
                    $match = $item;
                    break;
                }

                if(!isset($match)) {
                    
                    if ((@$item->query['view'] == 'puntos' && !isset($item->query['layout']))) {
                        $cats = ($item->params->get('hs_startcat')) ? $item->params->get('hs_startcat') : array();
                        if(in_array($needles['category'], $cats)) {
                            $match = $item;
                            break;
                        }
                    }
                }
            }

        
            if (!isset($match)) {
                foreach ($items as $item) {
                    if ((@$item->query['view'] == 'puntos' && !isset($item->query['layout']))) {
                        $match = $item;
                        break;
                    }
                }
            }
        }

		return $match;
	}

}