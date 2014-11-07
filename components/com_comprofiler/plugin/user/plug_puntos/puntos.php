<?php


defined('_JEXEC') or die('Restricted access');

class getpuntoTab extends cbTabHandler {

	private $puntos = null;

	public function getpuntosTab() {
		$this->cbTabHandler();
		$language = JFactory::getLanguage();
		$language->load('plg_plug_puntos', JPATH_ADMINISTRATOR);
		
		if(!file_exists(JPATH_BASE . '/components/com_puntos/includes/defines.php')) {
			return JText::_('PLG_CBpunto_PUNTOS_NOT_INSTALLED');
		}
	}

	private function getpunto() {
		if ($this->punto == null){
			$db = JFactory::getDBO();
            $query = $db->getQuery(true);
			$params = $this->params;

			$hslimit = $params->get('hslimit', "15");
            $query->select(array('m.id AS puntos_id', 'm.catid','m.name',
                'm.description_small','m.params','m.picture','m.picture_thumb',
                'm.gmlat', 'm.gmlng', 'm.created', 'c.*'))
                ->from('#__puntos_marker AS m')
                ->leftJoin('#__puntos_categorie AS c ON m.catid = c.id')
                ->where('m.created_by = ' . $db->quote($this->user->id))
                ->where('m.published = 1')
                ->order('m.created DESC');

			$db->setQuery($query, 0, $hslimit);

			$this->puntos = $db->loadObjectList();
		}

		return $this->puntos;
	}

	public function getSpotsList() {
		$params = $this->params; 
		$hsname = $params->get('hsname', "1");
		if ($hsname == 1) {
			$name = $this->user->name;
		} else {
			$name = $this->user->username;
		}

		$markrows = $this->getpuntos();
		
		$newspots = "<div id='new-spots'>";
		$newspots .= JText::_('PLG_CBPUNTOS_NEWEST_SPOTS_FROM') . " <strong>"  . $name . ":</strong>";
		$newspots .= "<br /><br />\n";

		for ($j = 0; $j < count($markrows); $j++) {
			$markrow = &$markrows[$j];
			$newspots .= '<span data-id="'.$markrow->puntos_id.'">'.$markrow->name.'</span>';
		}

		$newspots .= "</div>";
		return $newspots;
	}

	function getDisplayTab($tab, $user, $ui) {
		global $my;
        jimport('joomla.application.component.model');
        JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_puntos/models');
		$this->user = $user;
		$html = '';

		$document = JFactory::getDocument();
		$document->addStylesheet(JURI::root().'components/com_comprofiler/plugin/user/plug_puntos/puntos.css');
		$gmapsapi = 'http://maps.google.com/maps/api/js?sensor=true';
		$document->addScript($gmapsapi);
		JHTML::_('behavior.framework', true);

        JHTML::_('script', 'media/com_puntos/js/fixes.js');
        JHTML::_('script', 'media/com_puntos/js/libraries/infobubble/infobubble.js');
		JHTML::_('script', 'media/com_puntos/js/moo/Class.SubObjectMapping.js');
		JHTML::_('script', 'media/com_puntos/js/moo/Map.js');
		JHTML::_('script', 'media/com_puntos/js/moo/Map.Extras.js');
		JHTML::_('script', 'media/com_puntos/js/moo/Map.Marker.js');
		JHTML::_('script', 'media/com_puntos/js/moo/Map.InfoBubble.js');
        JHTML::_('script', 'media/com_puntos/js/moo/Map.Geocoder.js');

        JHTML::_('script', 'media/com_puntos/js/helpers/helper.js');
        JHTML::_('script', 'media/com_puntos/js/core.js');
        JHTML::_('script', 'media/com_puntos/js/sandbox.js');

        JHTML::_('script', 'media/com_puntos/js/modules/map.js');
        JHTML::_('script', 'media/com_puntos/js/modules/punto.js');
		JHTML::_('script', 'components/com_comprofiler/plugin/user/plug_puntos/puntosCB.js');
		
		
		$gmapheight = $this->params->get('gmapheight', "400");

		
		$html .= '<div id="puntos">';
		$html .= '<div id="map-holder">';
		$html .= '<div id="map_canvas" style="height: ' . $gmapheight . 'px; "></div>';
		$html .= "</div>";	
		$html .= $this->getSpotsList();
		$html .= '<div style="clear:both"></div>';
		$html .= "</div>";
		
		$markers = $this->preparepuntos($this->getpunto());
		if(!count($markers)) {
			return JText::_('PLG_CBPUNTOS_USER_HAS_NO_PUNTOS');
		}

		$start = "window.addEvent('domready', function() {";
        $start .= 'var punto = new compojoom.punto.core();';
        $start .= puntoUtils::getJSVariables();
		$start .= 'var markers = ' . json_encode($markers) . ';';
        $start .= "punto.addModule('hotspot',punto.DefaultOptions);";
        $start .= "punto.addModule('puntocb',markers,punto.DefaultOptions);";
        $start .= "punto.startAll();";
        $start .= "});";
		
		$document->addScriptDeclaration($start);
		
		
		return $html;
	}
	
	private function preparepunto($punto) {
		require_once(JPATH_BASE . '/components/com_punto/includes/defines.php' );
		require_once(JPATH_BASE . '/components/com_punto/utils.php' );
		require_once(JPATH_BASE . '/components/com_punto/helpers/route.php' );
		require_once(JPATH_ADMINISTRATOR . '/components/com_punto/helpers/punto.php' );
		
		$markers = array();
		foreach($punto as $key=> $hotspot) {
            $markers[] = puntoUtils::prepareHotspot($hotspot);
		}

        foreach($markers as $key => $marker) {
            if($marker->params->get('markerimage')) {
                $markers[$key]->icon =  PUNTO_PICTURE_CATEGORIES_PATH . $marker->params->get('markerimage');
            } else {
                $markers[$key]->icon = PUNTO_PICTURE_CATEGORIES_PATH . $marker->cat_icon;
            }
        }
		
		return $markers;
	}

}
