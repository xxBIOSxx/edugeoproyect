<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');


class PlgContentPuntos extends JPlugin
{
	private $hotspot = null;

	public function __construct(&$subject, $params)
	{
		$jlang = JFactory::getLanguage();
		$jlang->load('com_puntos', JPATH_SITE, 'en-GB', true);
		$jlang->load('com_puntos', JPATH_SITE, $jlang->getDefault(), true);
		$jlang->load('com_puntos', JPATH_SITE, null, true);

		parent::__construct($subject, $params);
	}

	
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
	
		if ($context == 'com_finder.indexer')
		{
			return true;
		}

		if (in_array(
			$context, array(
				'mod_custom.content', 'com_content.article', 'com_content.featured',
				'com_content.category', 'com_k2.item', 'com_k2.itemlist')
		))
		{
	
			if (strpos($article->text, 'puntos') === false)
			{
				return true;
			}

			$field = 'text';

			if ($context == 'com_content.featured')
			{
				$field = 'introtext';
			}

			$settings = array();

			$regex = '/{puntos\s+(.*?)}/i';


			preg_match_all($regex, $article->$field, $matches, PREG_SET_ORDER);


			if (isset($matches[0][1]))
			{
				$options = explode(' ', $matches[0][1]);

				foreach ($options as $option)
				{
					if (strpos($option, '=') === false)
					{
						continue;
					}

					$option = explode('=', $option);
					$settings[$option[0]] = $option[1];
				}
			}

			if (count($settings))
			{
				if (isset($settings['punto']))
				{
					$this->getpunto($settings['punto']);
					$output = $this->mappunto();
					$replace = $matches[0][0];
					$article->text = preg_replace("|$replace|", addcslashes($output, '\\$'), $article->text, 1);
				}
			}
		}

		return true;
	}

	
	private function getpunto($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('m.id as puntos_id, m.*, c.*')
			->from('#__puntos_marker  AS m')
			->leftJoin('#__puntos_categorie as c ON m.catid = c.id')
			->where('m.id = ' . $db->quote($id));

		$db->setQuery($query);
		$this->punto = $db->loadObject();

		return $this->punto;
	}

	
	public function mappunto()
	{
		$frontend = JPATH_BASE . '/components/com_puntos';
		require_once $frontend . '/includes/defines.php';
		require_once $frontend . '/helpers/route.php';
		JLoader::discover('puntosModel', $frontend . '/models');
		require_once JPATH_BASE . '/administrator/components/com_puntos/helpers/puntos.php';
		require_once $frontend . '/utils.php';

		$width = $this->params->get('map_width', '') ? $this->params->get('map_width', '') . 'px' : '100%';

		JHTML::_('behavior.framework', true);
		JHTML::_('behavior.tooltip');
		JHTML::_('stylesheet', 'media/com_puntos/css/puntos.css');

		$doc = JFactory::getDocument();
		$doc->addScript(puntosUtils::getGmapsUrl());

		puntosUtils::getJsLocalization();
		$domready = "window.addEvent('domready', function(){ \n";
		$this->punto = puntosUtils::preparepunto($this->punto);
		$punto = array(
			'id' => $this->punto->id,
			'latitude' => $this->punto->gmlat,
			'longitude' => $this->punto->gmlng,
			'title' => $this->punto->name,
			'icon' => (JURI::root() . "media/com_puntos/images/categories/") . $this->punto->cat_icon,
			'shadow' => (JURI::root() . "media/com_puntos/images/categories/") . $this->punto->cat_shadowicon
		);

		ob_start();

		$settings = puntosUtils::getJSVariables(true);

		$settings['mapStartZoom'] = $this->params->get('zoom', 12);
		$settings['centerType'] = 1;
		$settings['categories'] = array();

		require $frontend . '/views/json/tmpl/address.php';
		$address = ob_get_contents();
		ob_end_clean();
		$punto['description'] = '<h2>' . $this->punto->name . '</h2>'
			. '<div>' . $this->punto->description_small . '</div>'
			. '<div>' . preg_replace("@[\\r|\\n|\\t]+@", "", $address) . '</div>';

		$domready .= 'puntos = new compojoom.puntos.core();';
		$domready .= 'puntos.DefaultOptions = ' . json_encode($settings) . ';';
		$domready .= 'var punto = ' . json_encode($punto) . ';' . "\n";
		$domready .= "
				puntos.addSandbox('map_canvas', puntos.DefaultOptions);

				puntos.addModule('punto', punto, puntos.DefaultOptions);
				puntos.addModule('menu',puntos.DefaultOptions);
				puntos.startAll();";
		$domready .= "});";

		$doc->addScriptDeclaration($domready);

		JHTML::_('script', 'media/com_puntos/js/fixes.js');
		JHTML::_('script', 'media/com_puntos/js/spin/spin.js');
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

		JHTML::_('script', 'media/com_puntos/js/modules/punto/punto.js');
		JHTML::_('script', 'media/com_puntos/js/modules/menu.js');

		JHTML::_('script', 'media/com_puntos/js/helpers/slide.js');
		JHTML::_('script', 'media/com_puntos/js/helpers/tab.js');

		$map = '<div id="puntos" class="plg_content_puntos puntos">
                    <div id="map_cont" style="height: ' . $this->params->get('map_height', 400) . 'px; width: ' . $width . '">';
		ob_start();
		require_once $frontend . '/views/punto/tmpl/default_menu.php';
		$map .= ob_get_contents();
		ob_end_clean();

		$map .= '<div id="map_canvas" class="map_canvas"
                             style="height: ' . $this->params->get('map_height', 400) . 'px; width: ' . $width . '"></div>

                    </div>
                </div>';

		return $map;
	}
}
