<?php


defined('_JEXEC') or die ('Restricted access');


$html = array();
$html['count'] = $this->list['count'];
$html['catInfo'] = '';
if (!JRequest::getInt('start', 0, 'POST')) {
    if ($html['count']) {
        if (puntosHelper::getSettings('show_marker_count')) {
            $markerCount = ($html['count'] > 1) ? JText::_('COM_PUNTOS_THERE_ARE') : JText::_('COM_PUNTOS_THERE_IS');
            if (JRequest::getVar('task') == 'searchList') {
                $markerCount = JText::_('COM_PUNTOS_SEARCH_RETURNED');
            }
            $markerCount .= ' ' . $html['count'] . ' ';
            $markerCount .= ($html['count'] > 1) ? JText::_('COM_PUNTOS_PUNTOS') : JText::_('COM_PUNTOS_PUNTO');
            if (JRequest::getVar('cat')) {
                $markerCount .= ' ' . JText::_('COM_PUNTOS_IN_THIS_CATEGORY') . '.';
            } else {
                $markerCount .= ' ' . JText::_('COM_PUNTOS_IN_ALL_CATEGORIES') . '.';
            }
        }

        if (puntosHelper::getSettings('category_info')) {
            if (JRequest::getVar('cat')) {
                if (isset($this->list['puntos'][0]->cat_name)) {
                    $html['catInfo'] = '<h3>' . $this->list['puntos'][0]->cat_name . '</h3>';
                }
            }
            if (isset($this->list['puntos'][0]->cat_description) || PuntosHelper::getSettings('show_marker_count')) {
                $html['catInfo'] .= '<div class="info-content">';
                //				show the cat description only if we have a category
                if (JRequest::getVar('cat')) {
                    if (isset($this->list['puntos'][0]->cat_description)) {
                        if ($this->list['puntos'][0]->cat_description) {
                            $html['catInfo'] .= '<div>';
                            $html['catInfo'] .= $this->list['puntos'][0]->cat_description;
                            $html['catInfo'] .= '</div>';
                        }
                    }
                }
                if (puntosHelper::getSettings('show_marker_count')) {
                    $html['catInfo'] .= $markerCount;
                }
            }
            $html['catInfo'] .= '</div>';
        }

        if (puntosHelper::getSettings('show_marker_count') && !puntosHelper::getSettings('category_info')) {
            $html['catInfo'] = '<div class="info-content">';
            $html['catInfo'] .= $markerCount;
            $html['catInfo'] .= '</div>';
        }
    }
}

foreach ($this->list['puntos'] as $catid => $puntos) {

    foreach ($puntos as $value) {


        $this->punto = $value;
        ob_start();
        require('list_description.php');
        $description = ob_get_contents();
        ob_end_clean();

        $html['puntos'][$catid][$value->puntos_id] = array(
            'id' => $value->puntos_id,
            'latitude' => $value->gmlat,
            'longitude' => $value->gmlng,
            'title' => $value->name,
            'description' => $description,
            'icon' => PUNTOS_PICTURE_CATEGORIES_PATH . $value->cat_icon,
            'shadow' => PUNTOS_PICTURE_CATEGORIES_PATH . $value->cat_shadowicon
        );
    }
}


echo (json_encode($html));
