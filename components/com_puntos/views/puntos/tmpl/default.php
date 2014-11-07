<?php

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.framework', true);
JHTML::_('behavior.tooltip');
JHTML::_('stylesheet', 'media/com_puntos/css/puntos.css');

$this->setMootoolsLocale();


$doc = JFactory::getDocument();
$doc->addScript(puntosUtils::getGmapsUrl());

JHTML::_('script', 'media/com_puntos/js/libraries/mustache.js');
JHTML::_('script', 'media/com_puntos/js/libraries/infobubble/infobubble.js');
JHTML::_('script', 'media/com_puntos/js/fixes.js');

JHTML::_('script', 'media/com_puntos/js/spin/spin.js');
JHTML::_('script', 'media/com_puntos/js/moo/Class.SubObjectMapping.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.Extras.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.Marker.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.InfoBubble.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.Geocoder.js');
JHTML::_('script', 'media/com_puntos/js/helpers/helper.js');

JHTML::_('script', 'media/com_puntos/js/core.js');
JHTML::_('script', 'media/com_puntos/js/sandbox.js');

if (!puntosHelper::getSettings('hs_hide_categories', 0))
{
	JHTML::_('script', 'media/com_puntos/js/modules/categories.js');
}

JHTML::_('script', 'media/com_puntos/js/modules/loader.js');
JHTML::_('script', 'media/com_puntos/js/modules/map.js');
JHTML::_('script', 'media/com_puntos/js/modules/punto.js');

if (puntoS_PRO)
{
	JHTML::_('script', 'media/com_puntos/js/modules/kml.js');
}

JHTML::_('script', 'media/com_puntos/js/modules/menubar.js');
JHTML::_('script', 'media/com_puntos/js/modules/send.js');
JHTML::_('script', 'media/com_puntos/js/modules/print.js');

if (puntosHelper::getSettings('show_copy_link', 0))
{
	JHTML::_('script', 'media/com_puntos/js/modules/link.js');
}

if (puntosHelper::getSettings('custom_tiles', 0) && puntoS_PRO)
{
	JHTML::_('script', 'media/com_puntos/js/modules/tiles.js');
}

JHTML::_('script', 'media/com_puntos/js/modules/menu.js');
JHTML::_('script', 'media/com_puntos/js/helpers/tab.js');

if (puntosHelper::getSettings('show_quick_search', 1))
{
	JHTML::_('script', 'media/com_puntos/js/modules/search.js');
}

JHTML::_('script', 'media/com_puntos/js/modules/welcome.js');
JHTML::_('script', 'media/com_puntos/js/modules/navigator.js');
JHTML::_('script', 'media/com_puntos/js/helpers/slide.js');

if (puntosHelper::getSettings('mail_map', 1) == 1)
{
	JHTML::_('script', 'media/com_puntos/js/lightface/LightFace.js');
	JHTML::_('script', 'media/com_puntos/js/lightface/LightFace.Request.js');
}

$domready = "window.addEvent('domready', function(){ \n";
$domready .= "if (!('ontouchstart' in document.documentElement)) {
	document.documentElement.className += ' puntos-no-touch';
}";
$domready .= ' var puntos = new compojoom.puntos.core();';
$domready .= puntosUtils::getJsLocalization();
$domready .= puntosUtils::getJSVariables();

$domready .= "
if(window.location.hash == '') {
    window.location.hash = '!/catid='+puntos.DefaultOptions.startCat;
}
puntos.addModule('welcome',puntos.DefaultOptions);
puntos.addModule('loader','map_cont',puntos.DefaultOptions);
puntos.addModule('menu',puntos.DefaultOptions);";

if (!puntosHelper::getSettings('hs_hide_categories', 0))
{
	$domready .= "puntos.addModule('categories','#puntos-category-items', 'span',puntos.DefaultOptions);";
}
$domready .= "
puntos.addModule('menubar',puntos.DefaultOptions);
puntos.addModule('send',puntos.DefaultOptions);
puntos.addModule('print',puntos.DefaultOptions);";

if (puntosHelper::getSettings('show_copy_link', 0))
{
	$domready .= "puntos.addModule('link','map_cont', puntos.DefaultOptions);";
}
if (puntosHelper::getSettings('show_quick_search', 1))
{
	$domready .= "puntos.addModule('search',puntos.DefaultOptions);";
}

$domready .= "puntos.addModule('punto',puntos.DefaultOptions);";

if (puntoS_PRO)
{
	$domready .= "puntos.addModule('kml',puntos.DefaultOptions);";
}

$domready .= "puntos.addModule('map',puntos.DefaultOptions);";

if (puntosHelper::getSettings('custom_tiles', 0) && puntoS_PRO)
{
	$domready .= "puntos.addModule('tiles',puntos.DefaultOptions);";
}

$domready .= "puntos.addModule('navigator',puntos.DefaultOptions);

puntos.startAll();";

if (puntosHelper::getSettings('hs_start_fullscreen', 0))
{
	$domready .= "window.fireEvent('puntosResize');";
}
$domready .= "});";

$doc->addScriptDeclaration($domready);

?>

<?php if (puntosHelper::getSettings('show_page_title', 1)) : ?>
	<div class="componentheading<?php echo $this->escape(puntosHelper::getSettings('pageclass_sfx')); ?>">
		<?php echo $this->escape(puntosHelper::getSettings('page_title', 'puntos')); ?>
	</div>
<?php endif; ?>


<div class="puntos" id="puntos">

	<div id="puntos-navigation">

		<div class="navigation-bar">
			<?php if (puntosHelper::getSettings('show_copy_link', 0)) : ?>
				<span id="link-button" title="<?php echo JText::_('COM_puntoS_COPY_LINK'); ?>"></span>
			<?php endif; ?>
			<span id="center-button" title="<?php echo JTEXT::_('COM_puntoS_MARKER_CENTER'); ?>">
			</span>

			<?php if (puntosHelper::getSettings('hs_show_controllmenu', 1)) : ?>
				<span id="directions-button" title="<?php echo JText::_('COM_puntoS_DIRECTIONS'); ?>">
				</span>
			<?php endif; ?>

			<?php if (puntosHelper::getSettings('mail_map', 1) == 1) : ?>
				<span id="send-button" title="<?php echo JText::_('COM_puntoS_SEND'); ?>">
				</span>
			<?php endif; ?>
			<?php if (puntosHelper::getSettings('print_map', 1) == 1) : ?>
				<span id="print-button" title="<?php echo JText::_('COM_puntoS_PRINT'); ?>">
				</span>
			<?php endif; ?>

			<?php if (puntosHelper::getSettings('rss_enable', 1) == 1) : ?>
				<span id="rss-button">
					<a href="<?php echo JRoute::_('index.php?option=com_puntos&view=puntos&task=puntos.rss'); ?>"
					   target="_blank"
					   title="<?php echo JTEXT::_('COM_puntoS_FEED'); ?>">
						<img src="media/com_puntos/images/utils/rss.png"
						     alt="<?php echo JTEXT::_('COM_puntoS_FEED'); ?>"/>
					</a>
				</span>
			<?php endif; ?>
			<?php if (puntosHelper::getSettings('resize_map', 1) == 1) : ?>
				<span id="resize" title="<?php echo JText::_('COM_puntoS_RESIZE'); ?>">
				</span>
			<?php endif; ?>
		</div>


		<?php if (!puntosHelper::getSettings('hs_hide_categories', 0)) : ?>
			<div id="puntos-categories">
				<div id="cat-back"><!--slide back button--></div>
				<div id="puntos-categories-inner">
					<div id="puntos-category-items">
						<?php foreach ($this->cats as $key => $cat) : ?>
							<?php
							$path = JURI::root() . 'media/com_puntos/images/categories/' . $cat['cat_icon'];
							?>
							<span data-id="<?php echo $cat['id']; ?>" id="cat<?php echo $cat['id']; ?>"
							      class="puntos-category-item hasTip "
							      title="<?php echo $cat['text'] ?>::<?php echo ($cat['cat_description']) ? $cat['cat_description'] : ' '; ?>">
                                <img border="0" alt="Tooltip" src="<?php echo $path; ?>"/>
                            </span>

						<?php endforeach; ?>
					</div>
				</div>
				<div id="cat-forward"><!--slide forward button--></div>
			</div>
		<?php endif; ?>

		<?php // si el menú está oculto no mostrara el formulario de búsqueda, ya que no tenemos un lugar donde mostrar los resultados; ?>
		<?php if (puntosHelper::getSettings('show_quick_search', 1)) : ?>
			<form id="quick-search" class="form" action="">
				<input type="text" title="<?php echo JText::_('COM_puntoS_QUICK_SEARCH_TITLE'); ?>"/>
			</form>
		<?php endif; ?>

		<div class="clear-both"></div>
	</div>
	<div id="map_cont" style="height: <?php echo(puntosHelper::getSettings('map_height', 600)); ?>px;">

		<?php echo $this->loadTemplate('menu'); ?>

		<div id="map_canvas" class="map_canvas"
		     style="height: <?php echo puntosHelper::getSettings('map_height', 600); ?>px;"></div>

		<?php if (puntosHelper::getSettings('show_welcome_text', 1) && !(isset($_COOKIE['hide-welcome']))) : ?>
			<div id="puntos-welcome">
				<div style="margin:10px;">
					<?php echo puntosHelper::getSettings('welcome_text', 'Bienvenidos a Punto de acceso '); ?>
					<div class="clear-both"></div>
				</div>
				<div class="nav">
					<label for="hide-welcome">
						<input type="checkbox" value="1" name="hide-welcome" id="hide-welcome"/>
						<?php echo JText::_('COM_puntoS_HIDE_WELCOME'); ?>
					</label>

					<div style="float:right; margin-right:  10px;" id="close-welcome">
						<img src="media/com_puntos/images/utils/close.gif" width="14" height="13" alt="close"/>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<?php echo $this->loadTemplate('footer'); ?>
</div>
