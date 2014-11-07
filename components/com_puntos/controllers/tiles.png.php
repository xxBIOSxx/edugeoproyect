<?php



defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

require_once(JPATH_COMPONENT_ADMINISTRATOR . '/libraries/maps/utility.php');

class puntosControllerTiles extends JControllerLegacy
{

    public function create()
    {
        $input = JFactory::getApplication()->input;
        $user	= JFactory::getUser();
        $groups	= implode(',', $user->getAuthorisedViewLevels());
        $inputCats = $input->getString('cats', '', 'get');
        $search = $input->getString('search', '', 'get');
		$lang = $input->getString('hs-language', 'en-GB', 'get');
        $x = $input->getInt('x', 0, 'get');
        $y = $input->getInt('y', 0, 'get');
        $z = $input->getInt('z', 0, 'get');
        $size = 7;
        $filled = array();

        $tile = $this->getModel('Tile', 'puntosModel');
        $catModel = $this->getModel('Category', 'puntosModel');

        $cats = $catModel->getCategories();


        $file = JPATH_ROOT . '/media/com_puntos/tiles/'.$x. '_' .$y. '_' .$z. '_' . md5($x . '|' . $y . '|' . $z . '|' . $groups . '|' . $inputCats . '|' . $search . '|' . $lang) . '.png';



        if (!file_exists($file)) {

  
            $im = imagecreate(puntosMapUtility::TILE_SIZE, puntosMapUtility::TILE_SIZE);


            $trans = imagecolorallocate($im, 0, 0, 255);
            imagefill($im, 0, 0, $trans);
            imagecolortransparent($im, $trans);
            $black = imagecolorallocate($im, 0, 0, 0);

    
            foreach ($cats as $value) {
                $registry = new JRegistry($value->params);
                $params = $registry->toArray();

                if (isset($params['tile_marker_color'])) {
                    $rgb = explode(',', $params['tile_marker_color']);
                } else {
                    $rgb = array(0, 0, 0);
                }

                $colors[$value->id] = imagecolorallocate($im, $rgb[0], $rgb[1], $rgb[2]);
            }

            $rows = $tile->getItems();

            foreach ($rows as $row) {
                $point = puntosMapUtility::getPixelOffsetInTile($row['lat'], $row['lng'], $z);

                $color = $colors[$row['catid']];
                if (!isset($filled["{$point->x},{$point->y}"])) {
                    imagefilledellipse($im, $point->x, $point->y, $size, $size, $color);
                    imageellipse($im, $point->x, $point->y, $size, $size, $black);
                    $filled["{$point->x},{$point->y}"] = 1;
                }
            }

            JResponse::clearHeaders();
            JResponse::allowCache(true);
            JResponse::setHeader('Content-Type', 'image/png', true);
            JResponse::sendHeaders();

            imagepng($im, $file);
            readfile($file);

        } else {
          
            JResponse::clearHeaders();
            JResponse::allowCache(true);
            JResponse::setHeader('Content-Type', 'image/gif', true);
            JResponse::sendHeaders();
            readfile($file);
        }

        jexit();
    }

}