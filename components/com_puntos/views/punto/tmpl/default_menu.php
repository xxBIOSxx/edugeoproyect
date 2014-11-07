<?php


defined('_JEXEC') or die('Restricted access');
?>

<div id="slide_menu" style="<?php echo puntosHelper::getSettings('hs_show_controllmenu', 1) ? '' : 'display:none;'; ?>">
	<span id="toggle-menu" class="toggle-off" title="<?php echo JText::_('COM_PUNTOS_TOGGLE'); ?>">
	</span>
	<div class="puntos-actions" id="puntos-menu-actions" style="display:none">
		<label><input type="checkbox" id="all-puntos" /><?php echo JText::_('COM_PUNTOS_SHOW_ALL_puntoS'); ?></label>
	</div>
	<div id="tab-container">

		<div id="puntos-slide-tabs-back"><!--slide back button--></div>
			<div id="tab-container-inner">
				<ul class="puntos-tabs" id="puntos-tabs"><li class="puntos-tab" id="tab-search" data-id="search"><span><img src="<?php echo JURI::root(); ?>/media/com_puntos/images/utils/search.png" alt="saerch" title="search" /></span></li></ul>
			</div>
		<div id="puntos-slide-tabs-forward"><!--slide forward button--></div>
	</div>
	<div class="clear-both"></div>

	<div class="puntos-tab-content" id="search-tab-container" data-id="search">
		<div class="search-actions">
			<span class="active" data-id="search-directions"><?php echo JText::_('COM_PUNTOS_SEARCH_DIRECTIONS'); ?></span><span data-id="search-address"><?php echo JText::_('COM_puntoS_SEARCH_ADDRESS'); ?></span>
		</div>
		<form id="search-directions" action="" class="form active menu">
			<div class="" style="width:100%">
				<label for="directions-departure">
					<?php echo JText::_('COM_puntoS_START_ADDRESS'); ?>
				</label>
				<input type="text" id="directions-departure" title="<?php echo JText::_('COM_PUNTOS_YOUR_START_ADDRESS'); ?>" class="required" />
				<label for="directions-arrival">
					<?php echo JText::_('COM_puntoS_END_ADDRESS'); ?>
				</label>
				<input type="text" id="directions-arrival" title="<?php echo JText::_('COM_PUNTOS_YOUR_END_ADDRESS'); ?>" class="required"/>

				<button class="sexybutton right" type="submit">
					<span>
						<span><?php echo JText::_('COM_PUNTOS_GET_DIRECTIONS'); ?></span>
					</span>
				</button>
			</div>
			<div id="directions-display"></div>
		</form>
		<form id="search-address" class="form menu" action="">
			<input type="text" id="search-address-input" title="<?php echo JText::_('COM_PUNTOS_ADDRESS'); ?>" class="required"/>
			<button class="sexybutton right" type="submit">
				<span>
					<span><?php echo JText::_('COM_PUNTOS_SUBMIT'); ?></span>
				</span>
			</button>
			<div id="puntos-address-result">

			</div>
		</form>
	</div>

</div>
