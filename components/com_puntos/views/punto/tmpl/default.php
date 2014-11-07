<?php

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.framework', true);
JHTML::_('behavior.tooltip');
JHTML::_('stylesheet', 'media/com_puntos/css/puntos.css');
$this->setMootoolsLocale();

$doc = JFactory::getDocument();
$doc->addScript(puntosUtils::getGmapsUrl());

JHTML::_('script', 'media/com_puntos/js/fixes.js');
JHTML::_('script', 'media/com_puntos/js/libraries/infobubble/infobubble.js');
JHTML::_('script', 'media/com_puntos/js/moo/Class.SubObjectMapping.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.Extras.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.Marker.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.InfoBubble.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.Geocoder.js');
JHTML::_('script', 'media/com_puntos/js/helpers/helper.js', '');

JHTML::_('script', 'media/com_puntos/js/core.js');
JHTML::_('script', 'media/com_puntos/js/sandbox.js');

JHTML::_('script', 'media/com_puntos/js/modules/map.js', '');
JHTML::_('script', 'media/com_puntos/js/modules/punto/punto.js');
JHTML::_('script', 'media/com_puntos/js/modules/menu.js');

JHTML::_('script', 'media/com_puntos/js/helpers/slide.js');
JHTML::_('script', 'media/com_puntos/js/helpers/tab.js');


$doc = JFactory::getDocument();
$punto = array(
	'id' => $this->punto->id,
	'latitude' => $this->punto->gmlat,
	'longitude' => $this->punto->gmlng,
	'title' => $this->punto->name,
	'description' => $this->loadTemplate('description'),
	'icon' => $this->category->cat_icon,
	'shadow' => $this->category->cat_shadowicon
);
puntosUtils::getJsLocalization();
$domready = "window.addEvent('domready', function(){ \n";
$domready .= 'var puntos = new compojoom.puntos.core();';
$domready .= puntosUtils::getJSVariables() . "\n";
$domready .= 'var punto = ' . json_encode($punto) . ';' . "\n";
$domready .="
puntos.addSandbox('map_canvas', puntos.DefaultOptions);
puntos.addModule('map',puntos.DefaultOptions);
puntos.addModule('punto', punto, puntos.DefaultOptions);
puntos.addModule('menu',puntos.DefaultOptions);
puntos.startAll();
var tabs = new compojoom.puntos.tab('tab-details',{
			tabSelector: '.tab-details-tab',
			contentSelector: '.tab-details-tab-content'
		});;
";
$domready .= "});";

$doc->addScriptDeclaration($domready);
?>
<div id="puntos" class="puntos">
	<h2 class="componentheading"><?php echo $this->punto->name; ?></h2>


	<div id="tab-details">


		<ul class="tab-details-tabs"><li class="tab-details-tab" data-id="map"><span><?php echo JText::_('COM_PUNTOS_MAP_SINGLE_VIEW_TAB'); ?></span></li><?php if ($this->punto->picture) : ?><li class="tab-details-tab" data-id="photo"><span><?php echo JText::_('COM_puntoS_PHOTO'); ?></span></li><?php endif; ?></ul>

		<div class="clear-both"></div>
		<div id="puntos-navigation" style="display:none" ></div>
		<div class="tab-details-tab-content" data-id="map">
			<?php echo $this->loadTemplate('one_line_address'); ?>
			<div id="map_cont" class="single-view" style="width: 100%; height: 400px;position: relative;">
				<?php echo $this->loadTemplate('menu'); ?>
				<div id="map_canvas" class="map_canvas" style="height: <?php echo puntosHelper::getSettings('map_height', 600); ?>px;"></div>
			</div>
		</div>
		<?php if ($this->punto->picture) : ?>
			<div class="tab-details-tab-content" data-id="photo">
				<div class="puntos-image">
					<?php
					echo "<img src=\"" . $this->punto->picture . "\" title=\"" . $this->punto->name . "\" alt=\"" . $this->punto->name . "\" />";
					?>
				</div>
				<div class="clear-both"></div>
		</div>
	<?php endif; ?>


	<div class="puntos-description">
        <?php echo $this->punto->description_small; ?>
		<?php echo $this->punto->description; ?>
	</div>

	<div class="punto-creation-info">
		<?php if ($this->settings->get('show_author')) : ?>
			<?php echo JTEXT::_('COM_PUNTOS_POSTED_BY'); ?>
				<?php if ($this->profile) : ?>
					<a href="<?php echo $this->profile; ?>">
				<?php endif; ?>

			<?php
				if($this->punto->created_by) {
					$user = JFactory::getUser($this->punto->created_by);
					$userName = $user->name;
				} else {
					$userName = $this->punto->created_by_alias;
				}

				echo $userName;
			?>

				<?php if ($this->profile) : ?>
					</a>
				<?php endif; ?>
		<?php endif; ?>

		<?php if ($this->settings->get('show_date')) : ?>
			<?php echo JText::_('COM_PUNTOS_ON'); ?>
			<?php echo $this->punto->created; ?>
		<?php endif; ?>
	</div>

	<div class="clear-both"></div>

	<?php if ($this->hotid != ""): ?>
		<div class="puntos-backlink">
			<a href="<?php echo $this->backlink; ?>" title="<?php echo $this->punto->name; ?>">
				<?php echo JTEXT::_('COM_PUNTOS_BACK_TO_PUNTOS'); ?>
			</a>
		</div>

		<div class="clear-both"></div>
	<?php endif; ?>

	<?php if (puntosHelper::getSettings('josc_support', '0') == 1) : ?>
        <?php
            $file=JPATH_BASE .'/administrator/components/com_comment/plugins/com_puntos/puntos.php';
            if(file_exists($file)) :
        ?>
		<div class="puntos-comments">
			<?php
			JLoader::discover('ccommentHelper', JPATH_ROOT . '/components/com_comment/helpers');
			echo ccommentHelperUtils::commentInit('com_puntos', $this->punto, $this->punto->params);
			?>
		</div>
        <?php else : ?>
            <div class="alert alert-error">
                <?php echo JText::_('COM_PUNTOS_CCOMMENT_ENABLED_BUT_NO_CCOMMENT_INSTALLED'); ?>
            </div>
        <?php endif; ?>
	<?php endif; ?>

	<?php require_once(JPATH_COMPONENT . '/views/puntos/tmpl/default_footer.php'); ?>

</div>
</div>