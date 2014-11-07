<?php


defined('_JEXEC') or die('Restricted access');


class PuntosUtils
{
	private static $frontCategories;

	
	public static function isUserInGroups($groups = array())
	{
		$user = JFactory::getUser();

		$userGroups = $user->getAuthorisedGroups();

		if (array_intersect($groups, $userGroups))
		{
			return true;
		}

		return false;
	}

	
	public static function getLocalDate($date)
	{
		$format = PuntosHelper::getSettings('date_format', 'Y-m-d H:i:s');
		$formattedDate = JHtml::_('date', $date, $format, true, true);

		return $formattedDate;
	}

	
	public static function get_front_categories()
	{
		if (!isset(self::$frontCategories))
		{
			$db = JFactory::getDBO();
			$query = "SELECT id, id AS value, cat_name AS text, cat_description, count, cat_icon FROM
			#__Puntos_categorie WHERE published='1' ORDER BY " . PuntosHelper::getSettings('category_ordering', 'id ASC');
			$db->setQuery($query);
			$rows = $db->loadAssocList('id');

			if ($db->getErrorNum())
			{
				echo $db->stderr();

				return false;
			}

			self::$frontCategories = $rows;
		}

		return self::$frontCategories;
	}

	
	public static function getJSVariables($array = false)
	{
		$settings = array();
		$app = JFactory::getApplication();
		$uri = JUri::getInstance();
		$settings['rootUrl'] = JUri::root();
		$settings['baseUrl'] = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port', 'path'));
		$settings['mapStartPosition'] = PuntosHelper::getSettings('map_startposition', 'Karlsruhe, Germany');
		$settings['mapStartZoom'] = (int) PuntosHelper::getSettings('map_startzoom', 4);

		$settings['centerType'] = (int) PuntosHelper::getSettings('map_centertyp', 1);

		$settings['customUserZoom'] = (int) PuntosHelper::getSettings('map_center_user_zoom', 15);

		
		if ($settings['centerType'] === 2 && $app->input->getCmd('view') == 'Puntos')
		{
			$settings['highAccuracy'] = (int) PuntosHelper::getSettings('high_accuracy', 0);
			$location = PuntosHelperLocation::getUserLocation();

			if ($location)
			{
				if ($location->latitude && $location->longitude)
				{
					$settings['mapStartPosition'] = $location->latitude . ',' . $location->longitude;

		
					$settings['mapStartZoom'] = 10;
				}
			}
		}

		$settings['searchZoom'] = (int) PuntosHelper::getSettings('search_zoom', 14);

		if ($app->isSite() && $app->input->getCmd('view') == 'Puntos')
		{
			$settings['startCat'] = implode(';', PuntosHelper::getSettings('hs_startcat', 1));
		}

		$settings['staticMapWidth'] = (int) PuntosHelper::getSettings('map_static_width', 500);
		$settings['staticMapHeight'] = (int) PuntosHelper::getSettings('map_static_height', 300);
		$settings['getDirections'] = (int) PuntosHelper::getSettings('routenplaner', 1);
		$settings['gmControl'] = (int) PuntosHelper::getSettings('gm_control', '1');
		$settings['gmControlPos'] = PuntosHelper::getSettings('gm_control_pos', 'topLeft');
		$settings['mapType'] = (int) PuntosHelper::getSettings('map_type', 1);
		$settings['panControl'] = (int) PuntosHelper::getSettings('panControl', 1);
		$settings['zoomControl'] = (int) PuntosHelper::getSettings('zoomControl', 1);
		$settings['mapTypeControl'] = (int) PuntosHelper::getSettings('mapTypeControl', 1);
		$settings['scaleControl'] = (int) PuntosHelper::getSettings('scaleControl', 1);
		$settings['streetViewControl'] = (int) PuntosHelper::getSettings('streetViewControl', 1);
		$settings['overviewMapControl'] = (int) PuntosHelper::getSettings('overviewMapControl', 1);
		$settings['scrollwheel'] = (int) PuntosHelper::getSettings('scrollwheel', 1);
		$settings['styledMaps'] = PuntosHelper::getSettings('styled_maps', '');
		$settings['userInterface'] = (int) PuntosHelper::getSettings('user_interface', 1);
		$settings['print'] = (int) PuntosHelper::getSettings('print_map', 1);
		$settings['resizeMap'] = (int) PuntosHelper::getSettings('resize_map', 1);
		$settings['mailMap'] = (int) PuntosHelper::getSettings('mail_map', 1);
		$settings['listLength'] = (int) PuntosHelper::getSettings('marker_list_length', 20);


		$settings['showDirections'] = (int) PuntosHelper::getSettings('show_marker_directions', 1);
		$settings['showAddress'] = (int) PuntosHelper::getSettings('show_address', 1);
		$settings['showCountry'] = (int) PuntosHelper::getSettings('show_address_country', 0);
		$settings['showZoomButton'] = (int) PuntosHelper::getSettings('show_zoom_button', 0);
		$settings['showAuthor'] = (int) PuntosHelper::getSettings('show_author', 1);
		$settings['showDate'] = (int) PuntosHelper::getSettings('show_date', 1);
		$settings['showMenu'] = (int) PuntosHelper::getSettings('hs_show_controllmenu', 1);
		$settings['numOfCatsToShow'] = (int) PuntosHelper::getSettings('number_of_cats_to_show', 4);
		$settings['categoryInfo'] = (int) PuntosHelper::getSettings('category_info', 4);
		$settings['showMarkerCount'] = (int) PuntosHelper::getSettings('show_marker_count', 4);
		$settings['weather'] = (int) PuntosHelper::getSettings('weather_api', 0);
		$settings['cloudsLayer'] = (int) PuntosHelper::getSettings('clouds_layer', 0);
		$settings['weatherTemperatureUnit'] = PuntosHelper::getSettings('weather_api_temperature_unit', 'CELSIUS');
		$settings['weatherWindSpeedUnit'] = (int) PuntosHelper::getSettings('weather_api_wind_speed_unit', 'KILOMETERS_PER_HOUR');
		$settings['weatherClickable'] = (int) PuntosHelper::getSettings('weather_api_data_clickable', 1);
		$settings['trafficLayer'] = (int) PuntosHelper::getSettings('traffic_layer', 0);
		$settings['transitLayer'] = (int) PuntosHelper::getSettings('transit_layer', 0);
		$settings['bicyclingLayer'] = (int) PuntosHelper::getSettings('bicycling_layer', 0);
		$settings['panoramioLayer'] = (int) PuntosHelper::getSettings('panoramio_layer', 0);
		$settings['panoramioUserId'] = (int) PuntosHelper::getSettings('panoramio_user_id', "");
		$settings['visualRefresh'] = (int) PuntosHelper::getSettings('visual_refresh', 1);
		$settings['draggableDirections'] = (int) PuntosHelper::getSettings('draggable_directions', 1);
		$settings['startClosedMenu'] = (int) PuntosHelper::getSettings('start_closed_menu', 0);

		$settings['categories'] = self::getCategoriesInfo();


		if ($array)
		{
			return $settings;
		}

		return 'Puntos.DefaultOptions = ' . json_encode($settings) . ';';
	}

	
	public static function getCategoriesInfo()
	{
		$catModel = JModelLegacy::getInstance('Category', 'PuntosModel');
		$boundaries = self::boundaries();
		$categories = $catModel->getCategories();

		foreach ($categories as $key => $category)
		{
			$categories[$key] = self::prepareCategory($category);

			if (isset($boundaries[$key]))
			{
				$categories[$key]->boundaries = $boundaries[$key];
			}
		}

		return $categories;
	}

	
	public static function boundaries()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('catid, MIN( gmlat ) AS south, MAX( gmlat ) AS north, MAX( gmlng ) AS east, MIN( gmlng ) AS west')
			->from('#__Puntos_marker')
			->group('catid')
			->where('published = 1');
		$db->setQuery($query);

		return $db->loadObjectList('catid');
	}

	
	public static function getJsLocalization()
	{
		$strings = array(
			'COM_PUNTOS_JS_DIRECTIONS',
			'COM_PUNTOS_GET_DIRECTIONS',
			'COM_PUNTOS_ZOOM',
			'COM_PUNTOS_TO',
			'COM_PUNTOS_FROM',
			'COM_PUNTOS_SUBMIT',
			'COM_PUNTOS_LOADING_DATA',
			'COM_PUNTOS_NO_PUNTOS_IN_CATEGORY',
			'COM_PUNTOS_MORE_PUNTOS',
			'COM_PUNTOS_READ_MORE',
			'COM_PUNTOS_CANCEL',
			'COM_PUNTOS_COULDNT_FIND_LOCATION',
			'COM_PUNTOS_ZERO_RESULTS_LOCATION',
			'COM_PUNTOS_PRINT',
			'COM_PUNTOS_SOMETHING_IS_WRONG',
			'COM_PUNTOS_ENTER_FULL_DESCRIPTION',
			'COM_PUNTOS_ENTER_SOBI2_ID',
			'COM_PUNTOS_ENTER_ARTICLE_ID',
			'COM_PUNTOS_GEOLOCATION_NO_SUPPORT',
			'COM_PUNTOS_DRAG_ME',
			'COM_PUNTOS_THERE_ARE',
			'COM_PUNTOS_THERE_IS',
			'COM_PUNTOS_EMAIL_THIS_MAP',
			'COM_PUNTOS_CLEAR_ROUTE',
			'COM_PUNTOS_SEND',
			'COM_PUNTOS_CLOSE',
			'COM_PUNTOS_SEARCH_RETURNED_NO_RESULTS',
			'COM_PUNTOS_POSTED_BY',
			'COM_PUNTOS_ON',
			'COM_PUNTOS_IN_YOUR_CURRENT_VIEW_THERE_ARE',
			'COM_PUNTOS_PUNTOS',
			'COM_PUNTOS_SEARCH_RESULTS_AROUND_THE_WORLD',
			'COM_PUNTOS_SEARCH_RETURNED_NO_RESULTS_IN_THIS_VIEW',
			'COM_PUNTOS_SEARCH_IN_YOUR_CURRENT_VIEW_RETURNED',
			'COM_PUNTOS_NO_LOCATIONS_IN_CURRENT_VIEW'
		);

		foreach ($strings as $string)
		{
			JText::script($string);
		}
	}

	/**
	 * Prepares a category for output
	 *
	 * @param   object  $category  - the category object
	 *
	 * @return mixed
	 */
	public static function prepareCategory($category)
	{
		$iconWebPath = (JURI::root() . "media/com_Puntos/images/categories/");

		if ($category->cat_icon)
		{
			$category->cat_icon = $iconWebPath . $category->cat_icon;
		}

		return $category;
	}

	
	public static function preparePunto($Punto)
	{
		$descriptionSmall = $Punto->description_small;

		if (PuntosHelper::getSettings('marker_allow_plugin', 0) == 1)
		{
			$descriptionSmall = JHTML::_('content.prepare', $descriptionSmall, '');
		}

		$Punto->postdate = PuntosUtils::getLocalDate($Punto->created);

		if ($Punto->picture_thumb)
		{
			$Punto->picture_thumb = PuntoS_THUMB_PATH . $Punto->picture_thumb;
		}

		if ($Punto->picture)
		{
			$Punto->picture = PuntoS_PICTURE_PATH . $Punto->picture;
		}

		$parameters = new JRegistry;
		$parameters->loadString($Punto->params);
		$Punto->params = $parameters;

		$Punto->link = self::createLink($Punto);

		$descriptionSmall = self::sef($descriptionSmall);
		$Punto->description_small = $descriptionSmall;

		return $Punto;
	}


	private static function sef($content)
	{
		$app = JFactory::getApplication();

		if ($app->getCfg('sef') == '0')
		{
			return $content;
		}

	
		$base = JURI::base(true) . '/';

		$regex = '#href="index.php\?([^"]*)#m';
		$content = preg_replace_callback($regex, array('self', 'route'), $content);


		$protocols = '[a-zA-Z0-9]+:';
		$regex = '#(src|href|poster)="(?!/|' . $protocols . '|\#|\')([^"]*)"#m';
		$content = preg_replace($regex, "$1=\"$base\$2\"", $content);

		$regex = '#(onclick="window.open\(\')(?!/|' . $protocols . '|\#)([^/]+[^\']*?\')#m';
		$content = preg_replace($regex, '$1' . $base . '$2', $content);

	
		$regex = '#(onmouseover|onmouseout)="this.src=([\']+)(?!/|' . $protocols . '|\#|\')([^"]+)"#m';
		$content = preg_replace($regex, '$1="this.src=$2' . $base . '$3$4"', $content);

	
		$regex = '#style\s*=\s*[\'\"](.*):\s*url\s*\([\'\"]?(?!/|' . $protocols . '|\#)([^\)\'\"]+)[\'\"]?\)#m';
		$content = preg_replace($regex, 'style="$1: url(\'' . $base . '$2$3\')', $content);


		$regex = '#(<param\s+)name\s*=\s*"(movie|src|url)"[^>]\s*value\s*=\s*"(?!/|' . $protocols . '|\#|\')([^"]*)"#m';
		$content = preg_replace($regex, '$1name="$2" value="' . $base . '$3"', $content);

	
		$regex = '#(<param\s+[^>]*)value\s*=\s*"(?!/|' . $protocols . '|\#|\')([^"]*)"\s*name\s*=\s*"(movie|src|url)"#m';
		$content = preg_replace($regex, '<param value="' . $base . '$2" name="$3"', $content);

		$regex = '#(<object\s+[^>]*)data\s*=\s*"(?!/|' . $protocols . '|\#|\')([^"]*)"#m';
		$content = preg_replace($regex, '$1data="' . $base . '$2"$3', $content);

		return $content;
	}


	protected static function route(&$matches)
	{
		$url = $matches[1];
		$url = str_replace('&amp;', '&', $url);
		$route = JRoute::_('index.php?' . $url);

		return 'href="' . $route;
	}

	
	public static function createLink($Punto)
	{
		$PuntosLink = '';

		if (!is_object($Punto->params))
		{
			$parameters = new JRegistry;
			$parameters->loadString($Punto->params);
			$Punto->params = $parameters;
		}

		$globalReadMore = PuntosHelper::getSettings('Punto_detailpage', 1);
		$PuntoReadMore = $Punto->params->get('show_readmore');

		if (($PuntoReadMore == null && $globalReadMore) || $PuntoReadMore)
		{
			if ($Punto->params->get('link_to'))
			{
				$plugin = JPluginHelper::getPlugin('Puntoslinks', $Punto->params->get('link_to'));

				if (is_object($plugin))
				{
					JPluginHelper::importPlugin('Puntoslinks', $Punto->params->get('link_to'));
					$dispatcher = JDispatcher::getInstance();
					$links = $dispatcher->trigger('onCreateLink', $Punto->params->get('link_to_id'));
					$PuntosLink = $links[0];
				}
				else
				{
					$PuntosLink = self::linkToPunto($Punto);
				}
			}
			else
			{
				$PuntosLink = self::linkToPunto($Punto);
			}
		}

		return $PuntosLink;
	}

	
	public static function linkToPunto($Punto)
	{
		$cats = PuntosUtils::get_front_categories();

		if (isset($cats[$Punto->catid]))
		{
			$urlcat = $Punto->catid . ':' . JFilterOutput::stringURLSafe($cats[$Punto->catid]['text']);
		}

		$urlid = $Punto->Puntos_id . ':' . JFilterOutput::stringURLSafe($Punto->name);
		$PuntosLink = JRoute::_(PuntosHelperRoute::getPuntoRoute($urlid, $urlcat), false);

		return $PuntosLink;
	}

	
	public static function createFeed()
	{
		jimport('joomla.filesystem.folder');
		require_once JPATH_COMPONENT_ADMINISTRATOR . "/libraries/rss/feedcreator.php";

		$rss = new UniversalFeedCreator;
		$folderPath = JPATH_SITE . '/media/com_Puntos/rss';
		$folderExists = JFolder::exists($folderPath);

		if (!$folderExists)
		{
			JFolder::create($folderPath);
		}

		$rss->useCached("RSS2.0", $folderPath . '/Puntosfeed.xml');

		$rss->title = JURI::Base() . " - " . JTEXT::_('Newest Puntos');
		$rss->description = JTEXT::_('New Puntos at') . ' ' . JURI::Base();
		$rss->link = JURI::Base();

		$image = new FeedImage;
		$image->title = JURI::Base() . " " . "Puntos";
		$image->url = PuntosHelper::getSettings('rss_logopath', JURI::Base() . "media/com_Puntos/images/utils/logo.jpg");
		$image->link = JURI::Base();
		$image->description = JTEXT::_('Feed provided by') . " " . JURI::Base() . ". " . JTEXT::_('Click to visit');
		$rss->image = $image;
		$hs_show_address = PuntosHelper::getSettings('show_address', 1);
		$hs_show_address_country = PuntosHelper::getSettings('show_address_country', 0);
		$hs_show_author = PuntosHelper::getSettings('show_author', 1);
		$hs_show_detailpage = PuntosHelper::getSettings('Punto_detailpage', 1);
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__Puntos_marker WHERE published = 1 ORDER BY created DESC", 0, PuntosHelper::getSettings('rss_limit', "100"));
		$rows = $db->loadObjectList();

		if ($rows != null)
		{
			foreach ($rows as $row)
			{
				$row->Puntos_id = $row->id;
				$name = htmlspecialchars("$row->name");
				$street = htmlspecialchars("$row->street");
				$plz = htmlspecialchars("$row->plz");
				$town = htmlspecialchars("$row->town");
				$country = htmlspecialchars("$row->country");

				if ($hs_show_address == "1")
				{
					if ($hs_show_address_country == "1")
					{
						$adress = "$street, $plz $town<br />$country<br /><br />";
					}
					else
					{
						$adress = "$street, $plz $town<br /><br />";
					}
				}

				if ($hs_show_detailpage == "1")
				{
					$mlink = self::createLink($row);
				}

				if ($hs_show_author == "1")
				{
					$autor = JURI::Base();

					if ($row->created_by_alias)
					{
						$autor = $row->created_by_alias;
					}
				}

				if (substr(ltrim($mlink), 0, 7) != 'http://')
				{
					$uri = JURI::getInstance();
					$base = $uri->toString(array('scheme', 'host', 'port'));
					$mlink = $base . $mlink;
				}

				$rss_item = new FeedItem;
				$rss_item->title = $name;
				$rss_item->link = $mlink;
				$rss_item->description = $adress . $row->description_small;
				$rss_item->date = JFactory::getDate($row->created)->toRFC822();
				$rss_item->source = JURI::Base();
				$rss_item->author = $autor;
				$rss->addItem($rss_item);
			}
		}

		$rss->cssStyleSheet = "http://www.w3.org/2000/08/w3c-synd/style.css";

		if (PuntosHelper::getSettings('rss_type', 0) == 0)
		{
			$rss->saveFeed("RSS2.0", $folderPath . '/Puntosfeed.xml');
		}
		elseif (PuntosHelper::getSettings('rss_type', 0) == 1)
		{
			$rss->saveFeed("RSS1.0", $folderPath . '/Puntosfeed.xml');
		}
		else
		{
			$rss->saveFeed("ATOM", $folderPath . '/Puntosfeed.xml');
		}
	}


	public static function getItemid($component = '', $view = '')
	{
		$appl = JFactory::getApplication();
		$menu = $appl->getMenu();
		$itemId = '';
		$items = $menu->getItems('component', $component);

		if ($view)
		{
			foreach ($items as $value)
			{
				if (strstr($value->link, 'view=' . $view))
				{
					$itemId = $value->id;
					break;
				}
			}
		}
		else
		{
			$itemId = isset($items[0]) ? $items[0]->id : '';
		}

		return $itemId;
	}

	public static function createThumb($picture)
	{
		jimport('joomla.filesystem.folder');

		$upload_path = (JPATH_ROOT . '/media/com_Puntos/images/Puntos/');
		$img_src = $upload_path . $picture;

		$thumb_dir = (JPATH_ROOT . '/media/com_Puntos/images/thumbs');
		$cache = true;

		if (!isset($img_src))
		{
			return "CopyLeft Edugeo";
		}

		if (!$image_infos = @getimagesize($img_src))
		{
			return JText::_('COM_PUNTOS_PIC_NOT_FOUND');
		}

		$width = $image_infos[0];
		$height = $image_infos[1];
		$type = $image_infos[2];
		$mime = $image_infos['mime'];

		$w = PuntosHelper::getSettings('picturethumb_width', "80");
		$h = PuntosHelper::getSettings('picturethumb_height', "80");
		$p = true;

		if (strstr($w, 'p'))
		{
			$p = (int) str_replace('p', '', $w);
			$w = null;
			$h = null;
		}

		if (isset($p) && !isset($w) && !isset($h))
		{
			if ($width < $height)
			{
				$new_width = ceil(($p / $height) * $width);

				$new_height = intval($p);
			}
			else
			{
				$new_height = ceil(($p / $width) * $height);

				$new_width = intval($p);
			}
		}
		else if (isset($w) && !isset($h) && !isset($p))
		{
		
			$new_width = intval($w);

			
			$new_height = ceil($height * $new_width / $width);
		}
		else if (isset($h) && !isset($w) && !isset($p))
		{
			$new_height = intval($h);

			$new_width = ceil($width * $new_height / $height);
		}
		else if (isset($h) && isset($w) && isset($p))
		{
			$new_height = intval($h);

			$new_width = intval($w);
		}
		else
		{
			return '';
		}

		if ($cache === true && !JFolder::exists($thumb_dir))
		{
			JFolder::create($thumb_dir);
		}

		switch ($type)
		{
			case 1:
				if (imagetypes() & IMG_GIF)
				{
					
					if (!JFile::exists($thumb_dir . '/thumb_' . $picture))
					{
					
						$orginal = imagecreatefromgif($img_src);

						
						$thumb = imagecreatetruecolor($new_width, $new_height);
						imagecopyresampled($thumb, $orginal, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

						if ($cache === true)
						{
					
							imagegif($thumb, $thumb_dir . '/thumb_' . $picture);
						}
					}
					else
					{
					
						JFile::read($thumb_dir . '/thumb_' . $picture);
					}
				}
				else
				{
					return JText::_('COM_PUNTOS_GIF_NOT_SUPPORTED');
				}
				break;
			case 2:
				if (imagetypes() & IMG_JPG)
				{
					if (!JFile::exists($thumb_dir . '/thumb_' . $picture))
					{
					
						$orginal = imagecreatefromjpeg($img_src);

					
						$thumb = imagecreatetruecolor($new_width, $new_height);
						imagecopyresampled($thumb, $orginal, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

						if ($cache === true)
						{
						
							imagejpeg($thumb, $thumb_dir . '/thumb_' . $picture);
						}
					}
					else
					{
						
						JFile::read($thumb_dir . '/thumb_' . $picture);
					}
				}
				else
				{
					return JText::_('COM_PUNTOS_JPG_NOT_SUPPORTED');
				}
				break;
			case 3:
				if (imagetypes() & IMG_PNG)
				{
					if (!JFile::exists($thumb_dir . '/thumb_' . $picture))
					{
						
						$orginal = imageCreateFromPNG($img_src);

						
						$thumb = imagecreatetruecolor($new_width, $new_height);
						imagecopyresampled($thumb, $orginal, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

						if ($cache === true)
						{
							
							imagepng($thumb, $thumb_dir . '/thumb_' . $picture);
						}
					}
					else
					{
						file_get_contents($thumb_dir . '/thumb_' . $picture);
					}
				}
				else
				{
					return JText::_('COM_PUNTOS_PNG_NOT_SUPPORTED');
				}
				break;
			default:
				return JText::_('COM_PUNTOS_PIC_NOT_SUPPORTED');
		}

		return false;
	}


	public static function uploadPicture($picture)
	{
		if (is_array($picture) && isset($picture['tmp_name']))
		{
			$errormsg = '';
			$imageinfo = getimagesize($picture['tmp_name']);

			if ($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/png')
			{
				$errormsg .= "lo siento esa imagen no es soportada - gif, jpg, png";

				return false;
			}
			else
			{
				$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html", ".txt", ".dhtml", ".htm", ".doc", ".asp", ".net", ".js", ".rtf");

				foreach ($blacklist as $item)
				{
					if (preg_match("/$item\$/i", $picture['name']))
					{
						$errormsg .= "por favor suba PHP files\n";

						return false;
					}
				}

				$upload_path = (JPATH_ROOT . '/media/com_Puntos/images/Puntos/');
				$upload_image = $upload_path . basename($picture['name']);

				if (JFile::upload($picture['tmp_name'], $upload_image))
				{
					echo "<script> alert('File " . basename($picture['name']) . "sucessfully uploaded'); window.histroy.go(-1); </script>\n";
					$errormsg .= "sucessfull upload at $upload_path";
				}
				else
				{
					echo "<script> alert('Error uploading " . basename($picture['name']) . "!!!'); window.histroy.go(-1); </script>\n";
					$errormsg .= "failed to upload at $upload_path";
				}

				return basename($picture['name']);
			}
		}

		return false;
	}


	public static function getGmapsUrl()
	{
		$url = 'https://maps.googleapis.com/maps/api/js?sensor=true';
		$libraries = array();
		$key = PuntosHelper::getSettings('api_key', '');

		if ($key)
		{
			$url .= '&key=' . $key;
		}

		if (PuntosHelper::getSettings('weather_api') || PuntosHelper::getSettings('clouds_layer'))
		{
			$libraries[] = 'weather';
		}

		if (PuntosHelper::getSettings('panoramio_layer', 0))
		{
			$libraries[] = 'panoramio';
		}

		if (count($libraries))
		{
			$url .= '&libraries=' . implode(',', $libraries);
		}

		return htmlspecialchars($url);
	}
}
