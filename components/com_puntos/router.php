<?php


defined('_JEXEC') or die('Restricted access');


function puntosBuildRoute(&$query) {
	$segments = array();


	$appl = JFactory::getApplication();
	$menu = $appl->getMenu();
	if (empty($query['Itemid'])) {
		$menuItem = $menu->getActive();
	} else {
		$menuItem = $menu->getItem($query['Itemid']);
	}
	$mView = (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
	$mCatid = (empty($menuItem->query['catid'])) ? null : $menuItem->query['catid'];
	$mId = (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];

	if (isset($query['view'])) {
		$view = $query['view'];
		if (empty($query['Itemid'])) {
			$segments[] = $query['view'];
		}
		unset($query['view']);
	};

	if (($mView == 'punto') && (isset($query['id'])) && ($mId == intval($query['id']))) {
		unset($query['view']);
		unset($query['catid']);
		unset($query['id']);
	}

	if (isset($query['catid'])) {
		
		if ((($view == 'punto') and ($mView != 'category'))) {
			$segments[] = $query['catid'];
		}
		unset($query['catid']);
	}

    if (isset($query['layout'])) {
        $segments[] = $query['layout'];
        unset($query['layout']);
    }

	if (isset($query['id'])) {
		if (empty($query['Itemid'])) {
			$segments[] = $query['id'];
		} else {
			if (isset($menuItem->query['id'])) {
				if ($query['id'] != $mId) {
					$segments[] = $query['id'];
				}
			} else {
				$segments[] = $query['id'];
			}
		}
		unset($query['id']);
	}

	return $segments;
}

function puntosParseRoute($segments) {
	$vars = array();

	
	$appl = JFactory::getApplication();
	$menu = $appl->getMenu();
	$item = $menu->getActive();

	$count = count($segments);

	if ($item->query['view'] == 'puntos') {
		$vars['view'] = 'punto';
	}

    if (count($segments) == 1) {
        $vars['id'] = $segments[0];

        if (JFactory::getApplication()->input->getCmd('task') == 'form.edit') {
            $vars['view'] = 'form';
            $vars['task'] = 'edit';
        }
    }

    if ($segments[0] == 'edit') {
        $vars['view'] = 'form';
        $vars['task'] = 'edit';
    }

    if (isset($segments[1])) {
        if (count($segments) > 1) {
            $cat = explode(':', $segments[0]);
            $vars['catid'] = $cat[0];
            $id = explode(':', $segments[1]);
            $vars['id'] = $id[0];
        }
    }

	return $vars;
}
