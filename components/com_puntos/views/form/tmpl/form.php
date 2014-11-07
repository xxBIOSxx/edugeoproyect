<?php


defined('_JEXEC') or die('Restricted access');

$doc = JFactory::getDocument();
$doc->addScript(puntosUtils::getGmapsUrl());

JHTML::_('stylesheet', 'media/com_puntos/css/puntos.css');

JHTML::_('behavior.framework', true);
JHTML::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHTML::_('behavior.calendar');

$this->setMootoolsLocale();
JHTML::_('script', 'media/com_puntos/js/fixes.js');
JHTML::_('script', 'media/com_puntos/js/moo/Class.SubObjectMapping.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.Extras.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.Marker.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.InfoWindow.js');
JHTML::_('script', 'media/com_puntos/js/moo/Map.Geocoder.js');

JHTML::_('script', 'media/com_puntos/js/helpers/helper.js');

JHTML::_('script', 'media/com_puntos/js/core.js');
JHTML::_('script', 'media/com_puntos/js/sandbox.js');
JHTML::_('script', 'media/com_puntos/js/modules/submit.js');

puntosUtils::getJsLocalization();
$options = puntosUtils::getJSVariables();
$domready = <<<ABC
window.addEvent('domready', function() {
	var puntos = new compojoom.puntos.core();

	{$options}
	puntos.DefaultOptions.centerType = 0;
	puntos.addSandbox('map-add', puntos.DefaultOptions);
	puntos.addModule('submit',puntos.DefaultOptions);
	puntos.startAll();
});
ABC;

$doc = JFactory::getDocument();
$doc->addScriptDeclaration($domready);
?>
<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		if (document.formvalidator.isValid(document.id('adminForm'))) {
			<?php echo $this->form->getField('puntoText')->save(); ?>
			Joomla.submitform(task);
		} else {
			return false;
		}
	}
</script>
<div id="puntos" class="puntos addform">
	<form action="<?php echo JRoute::_('index.php?option=com_puntos&view=form&id=' . (int) $this->item->id); ?>"
	      method="post" class="form form-validate" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_PUNTOS_NEW_punto') : JText::sprintf('COM_PUNTOS_EDIT_punto', $this->item->id); ?></legend>
			<ul class="adminformlist">
				<?php if (!$this->user->id) : ?>
					<li><?php echo $this->form->getLabel('created_by_alias'); ?>
						<?php echo $this->form->getInput('created_by_alias'); ?></li>
					<li><?php echo $this->form->getLabel('email'); ?>
						<?php echo $this->form->getInput('email'); ?></li>
				<?php endif; ?>
				<li><?php echo $this->form->getLabel('name'); ?>
					<?php echo $this->form->getInput('name'); ?></li>

				<li><?php echo $this->form->getLabel('catid'); ?>
					<?php echo $this->form->getInput('catid'); ?></li>

				<?php if (puntosHelper::getSettings('addhs_picture', 1)): ?>
					<?php if (puntosHelper::getSettings('addhs_picture', 1) == 2) : ?>
						<?php if (!$this->user->guest) : ?>
							<?php if ($this->item->picture_thumb) : ?>
								<li>
									<label><?php echo JText::_('COM_PUNTOS_CURRENT_PICTURE'); ?>:</label>
									<a href="<?php echo PUNTOS_PICTURE_PATH . $this->item->picture; ?>"
									   target="_blank">
										<img src="<?php echo PUNTOS_THUMB_PATH . $this->item->picture_thumb; ?>"
										     alt="<?php echo $this->item->name ?>"/>
									</a>
								</li>
							<?php endif; ?>


							<li><?php echo $this->form->getLabel('picture'); ?>
								<?php echo $this->form->getInput('picture'); ?></li>
						<?php endif; ?>
					<?php else : ?>
						<?php if ($this->item->picture_thumb) : ?>
							<li>
								<label><?php echo JText::_('COM_puntoS_CURRENT_PICTURE'); ?>:</label>
								<a href="<?php echo PUNTOS_PICTURE_PATH . $this->item->picture; ?>" target="_blank">
									<img src="<?php echo PUNTOS_THUMB_PATH . $this->item->picture_thumb; ?>"
									     alt="<?php echo $this->item->name ?>"/>
								</a>
							</li>
						<?php endif; ?>


						<li><?php echo $this->form->getLabel('picture'); ?>
							<?php echo $this->form->getInput('picture'); ?></li>
					<?php endif; ?>
				<?php endif; ?>
			</ul>

			<div class="clr"></div>
			<?php echo $this->form->getInput('puntoText'); ?>

			<div class="clr"></div>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_PUNTOS_LOCATION_DETAILS'); ?></legend>

			<div id="puntos-geolocation-info"></div>
			<div id="puntos-geolocation">
				<img src="<?php echo JURI::root() ?>/media/com_puntos/images/utils/person.png"
				     alt="find my location"
				     title="find my location"/>
			</div>
			<div>

				<ul class="adminformlist">
					<li><?php echo $this->form->getLabel('street'); ?>
						<?php echo $this->form->getInput('street'); ?></li>

					<?php if (puntosHelper::getSettings('user_interface', 1) == 0) : ?>

						<li><?php echo $this->form->getLabel('plz'); ?>
							<?php echo $this->form->getInput('plz'); ?></li>

						<li><?php echo $this->form->getLabel('town'); ?>
							<?php echo $this->form->getInput('town'); ?></li>
					<?php else: ?>
						<li><?php echo $this->form->getLabel('town'); ?>
							<?php echo $this->form->getInput('town'); ?></li>
						<li><?php echo $this->form->getLabel('plz'); ?>
							<?php echo $this->form->getInput('plz'); ?></li>
					<?php endif; ?>
					<li><?php echo $this->form->getLabel('country'); ?>
						<?php echo $this->form->getInput('country'); ?></li>
					<li>
						<?php echo $this->form->getLabel('sticky', 'params'); ?>
						<?php echo $this->form->getInput('sticky', 'params'); ?>
					</li>
				</ul>

				<div class="clr"></div>
				<div id="map-add"
				     title="<?php echo JText::_('COM_PUNTOS_MOVE_MARKER_DRAG'); ?>"></div>
				<div class="clr"></div>
				<div class="width-45 fltlft">
					<ul class="adminformlist">
						<li><?php echo $this->form->getLabel('gmlat'); ?>
							<?php echo $this->form->getInput('gmlat'); ?></li>
					</ul>
				</div>
				<div class="width-45 fltlft">
					<ul class="adminformlist">


						<li><?php echo $this->form->getLabel('gmlng'); ?>
							<?php echo $this->form->getInput('gmlng'); ?></li>
					</ul>
				</div>

			</div>
		</fieldset>

		<?php if ($this->user->authorise('core.edit.state', 'com_puntos')) : ?>
			<div class="width-40 fltrt span10">
				<?php echo JHtml::_('sliders.start', 'content-sliders-' . $this->item->id, array('useCookie' => 1)); ?>

				<?php echo JHtml::_('sliders.panel', JText::_('COM_PUNTOS_FIELDSET_PUBLISHING'), 'publishing-details'); ?>
				<fieldset class="panelform">
					<ul class="adminformlist">

						<li><?php echo $this->form->getLabel('publish_up'); ?>
							<?php echo $this->form->getInput('publish_up'); ?></li>

						<li><?php echo $this->form->getLabel('publish_down'); ?>
							<?php echo $this->form->getInput('publish_down'); ?></li>


						<li><?php echo $this->form->getLabel('published'); ?>
							<?php echo $this->form->getInput('published'); ?></li>

						<li><?php echo $this->form->getLabel('access'); ?>
							<?php echo $this->form->getInput('access'); ?></li>


						<li><?php echo $this->form->getLabel('language'); ?>
							<?php echo $this->form->getInput('language'); ?></li>

					</ul>
				</fieldset>

				<?php echo JHtml::_('sliders.end'); ?>
			</div>
		<?php endif; ?>

		<?php if (!JRequest::getInt('id')) : ?>
			<?php if ($this->recaptcha) : ?>
				<div class="clr"></div>
				<fieldset class="security">
					<legend><?php echo JText::_('COM_PUNTOS_SECURITY'); ?></legend>
					<div>
						<label for="recaptcha_response_field">
							<?php echo JText::_('COM_PUNTOS_CAPTCHA'); ?>
						</label>
						<?php echo $this->recaptcha; ?>
					</div>
				</fieldset>
			<?php endif; ?>
		<?php endif; ?>

		<button type="submit" class="sexybutton right" onclick="Joomla.submitbutton('punto.save')">
			<span>
				<span><?php echo JText::_('COM_PUNTOS_SUBMIT'); ?></span>
			</span>
		</button>

		<div class="clear-both"></div>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="return" value="<?php echo base64_encode($this->returnPage); ?>"/>
		<?php echo JHTML::_('form.token'); ?>
	</form>
</div>