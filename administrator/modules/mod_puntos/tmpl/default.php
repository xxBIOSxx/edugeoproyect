<?php

defined('_JEXEC') or die('Restricted access');


$doc = JFactory::getDocument();
$doc->addScript(puntosUtils::getGmapsUrl());

JHTML::_('stylesheet', 'media/com_puntos/css/puntos.css');
JHTML::_('stylesheet', 'media/com_puntos/css/mod_puntos.css');

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

JHTML::_('script', 'media/com_puntos/js/modules/punto.js');
JHTML::_('script', 'media/mod_puntos/js/modules/latestpuntos.js');

$doc = JFactory::getDocument();
puntosUtils::getJsLocalization();
$domready = "window.addEvent('domready', function(){ \n";

$domready .= 'puntos = new edugeo.puntos.core();';
$domready .= 'var latestpuntos = ' . modpuntosHelper::preparepuntos(modpuntosHelper::getpuntos()) . ';';
$domready .= puntosUtils::getJSVariables();
$domready .= "
puntos.addSandbox('map_canvas', puntos.DefaultOptions);
//puntos.addModule('map',puntos.DefaultOptions);
puntos.addModule('latestpuntos', latestpuntos, puntos.DefaultOptions);
//puntos.addModule('menu',puntos.DefaultOptions);
puntos.startAll();";


$domready .= "});";

$doc->addScriptDeclaration($domready);
JHtml::script('JTOOLBAR_EDIT');
?>

<div class="mod_puntos">
	<div id="map_cont" style="height: <?php echo $params->get('map_height', 300); ?>px;">

		<div id="map_canvas" class="map_canvas"
		     style="height: <?php echo $params->get('map_height', 300); ?>px;"></div>

	</div>
</div>