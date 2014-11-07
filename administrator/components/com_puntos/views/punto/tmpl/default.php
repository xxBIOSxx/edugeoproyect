<?php

defined('_JEXEC') or die('Restricted access');

$doc = JFactory::getDocument();
$doc->addScript(puntosUtils::getGmapsUrl());

$editor = JFactory::getEditor();
JHTML::_('behavior.framework');
JHTML::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

JHTML::_('stylesheet', 'media/com_puntos/css/puntos-backend.css');
JHTML::_('script', 'media/com_puntos/js/fixes.js' );

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

$localization = puntosUtils::getJsLocalization();
$options = puntosUtils::getJSVariables();
$domready = <<<ABC
window.addEvent('domready', function() {
	puntos = new compojoom.puntos.core();
	{$options}
	puntos.DefaultOptions.centerType = 0;
	puntos.addSandbox('map-add', puntos.DefaultOptions);
	puntos.addModule('submit',puntos.DefaultOptions);
	puntos.startAll();
});
ABC;

$doc->addScriptDeclaration($domready);
?>
<script type="text/javascript">
    Joomla.submitbutton = function (button) {
        var validator = new Form.Validator.Inline(document.id('adminForm'), {wrap:true});
        if (button == 'punto.cancel' || validator.validate()) {

        <?php echo $this->form->getField('puntoText')->save(); ?>
            Joomla.submitform(button, document.id('adminForm'));
            return true;
        }

        return false;
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_puntos&view=punto&id=' . (int)$this->punto->id); ?>"
      method="post" class="form" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <div class="width-60 span8 fltlft">
        <fieldset class="adminform">
            <legend><?php echo empty($this->punto->id) ? JText::_('COM_PUNTOS_NEW_PUNTO') : JText::sprintf('COM_PUNTOS_EDIT_PUNTO', $this->punto->id); ?></legend>
            <ul class="adminformlist">
                <li><?php echo $this->form->getLabel('name'); ?>
                    <?php echo $this->form->getInput('name'); ?></li>

                <li><?php echo $this->form->getLabel('catid'); ?>
                    <?php echo $this->form->getInput('catid'); ?></li>

                <li><?php echo $this->form->getLabel('published'); ?>
                    <?php echo $this->form->getInput('published'); ?></li>

                <li><?php echo $this->form->getLabel('access'); ?>
                    <?php echo $this->form->getInput('access'); ?></li>

                <?php if ($this->canDo->get('core.admin')): ?>
                <li><span class="faux-label"><?php echo JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL'); ?></span>
                    <div class="button2-left"><div class="blank">
                        <button type="button" onclick="document.location.href='#access-rules';">
                            <?php echo JText::_('JGLOBAL_PERMISSIONS_ANCHOR'); ?>
                        </button>
                    </div></div>
                </li>
                <?php endif; ?>

                <li><?php echo $this->form->getLabel('language'); ?>
                    <?php echo $this->form->getInput('language'); ?></li>

                <li><?php echo $this->form->getLabel('id'); ?>
                    <?php echo $this->form->getInput('id'); ?></li>

                <?php if($this->punto->picture_thumb) : ?>
                <li>
                    <label><?php echo JText::_('COM_PUNTOS_CURRENT_PICTURE'); ?>:</label>
                    <a href="<?php echo PUNTOS_PICTURE_PATH . $this->punto->picture; ?>" target="_blank">
                        <img src="<?php echo puntoS_THUMB_PATH . $this->punto->picture_thumb; ?>" alt="<?php echo $this->punto->name ?>" />
                    </a>
                </li>
                <?php endif; ?>

                <li><?php echo $this->form->getLabel('picture'); ?>
                    <?php echo $this->form->getInput('picture'); ?></li>
            </ul>
            <div class="clr"></div>


            <div class="clr"></div>
            <?php echo $this->form->getLabel('puntoText'); ?>
            <div class="clr"></div>
            <?php echo $this->form->getInput('puntoText'); ?>

            <div class="clr"></div>


        </fieldset>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_PUNTOS_LOCATION_DETAILS'); ?></legend>

            <div id="puntos-geolocation-info"></div>
            <div id="puntos-geolocation">
                <img src="<?php echo JURI::root() ?>/media/com_puntos/images/utils/person.png" alt="find my location"
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
                <div class="width-45 span5 fltlft">
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
        </fieldset>
    </div>

    <div class="width-40 span4 fltrt">
        <?php echo JHtml::_('sliders.start', 'content-sliders-' . $this->punto->id, array('useCookie' => 1)); ?>

        <?php echo JHtml::_('sliders.panel', JText::_('COM_PUNTOS_FIELDSET_PUBLISHING'), 'publishing-details'); ?>
        <fieldset class="panelform">
            <ul class="adminformlist">
                <li><?php echo $this->form->getLabel('created_by'); ?>
                    <?php echo $this->form->getInput('created_by'); ?></li>

                <li><?php echo $this->form->getLabel('created_by_alias'); ?>
                    <?php echo $this->form->getInput('created_by_alias'); ?></li>

                <li><?php echo $this->form->getLabel('created'); ?>
                    <?php echo $this->form->getInput('created'); ?></li>

                <li><?php echo $this->form->getLabel('publish_up'); ?>
                    <?php echo $this->form->getInput('publish_up'); ?></li>

                <li><?php echo $this->form->getLabel('publish_down'); ?>
                    <?php echo $this->form->getInput('publish_down'); ?></li>

                <?php if ($this->punto->modified_by) : ?>
                <li><?php echo $this->form->getLabel('modified_by'); ?>
                    <?php echo $this->form->getInput('modified_by'); ?></li>

                <li><?php echo $this->form->getLabel('modified'); ?>
                    <?php echo $this->form->getInput('modified'); ?></li>
                <?php endif; ?>


                <li><?php echo $this->form->getLabel('link_to'); ?>
                    <?php echo $this->form->getInput('link_to'); ?>
                </li>

            </ul>
        </fieldset>

        <?php echo $this->loadTemplate('params'); ?>

        <?php echo JHtml::_('sliders.end'); ?>

    </div>

    <div class="clr"></div>
    <?php if ($this->canDo->get('core.admin')): ?>
    <div class="width-100 span12 fltlft">
        <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->punto->id, array('useCookie'=>1)); ?>

        <?php echo JHtml::_('sliders.panel', JText::_('COM_PUNTOS_FIELDSET_RULES'), 'access-rules'); ?>
        <fieldset class="panelform">
            <?php echo $this->form->getLabel('rules'); ?>
            <?php echo $this->form->getInput('rules'); ?>
        </fieldset>

        <?php echo JHtml::_('sliders.end'); ?>
    </div>
    <?php endif; ?>
    <input type="hidden" name="task" value=""/>
    <?php echo JHTML::_('form.token'); ?>
</form>
