<?php

defined('_JEXEC') or die;


jimport( 'joomla.application.module.helper' );

$modules_left = JModuleHelper::getModules('ccc_'. $this->config->extensionPosition . '_left');
$modules_slider = JModuleHelper::getModules('ccc_'. $this->config->extensionPosition . '_slider');
$modules_promotion = JModuleHelper::getModules('ccc_'. $this->config->extensionPosition . '_promotion');

JHTML::_('behavior.tooltip');
JHTML::_('stylesheet', 'media/com_hotspots/ccc/css/ccc.css');
JHTML::_('script', 'media/com_hotspots/ccc/js/ccc.js');
?>
<div class="row-fluid">
    <div id="ccc_left" class="span8">
        <div id="ccc_left_inner">
            <?php
                foreach ($modules_left as $module) {
                    $output = JModuleHelper::renderModule($module);
                    echo $output;
                }
            ?>
        </div>
        <div id="ccc_promotion">
            <?php
            foreach ($modules_promotion as $module) {
                $output = JModuleHelper::renderModule($module);
                echo $output;
            }
            ?>
        </div>
    </div>
    <div id="ccc_right" class="span4">
        <div id="ccc_right_inner">
            <?php
                echo JHtml::_('sliders.start', 'panel-sliders', array('useCookie'=>'1'));

                foreach ($modules_slider as $module) {
                    $output = JModuleHelper::renderModule($module);
                    $params = new JRegistry;
                    $params->loadString($module->params);
                    if ($params->get('automatic_title', '0')=='0') {
                        echo JHtml::_('sliders.panel', JText::_($module->title), 'cpanel-panel-'.$module->name);
                    }
                    elseif (method_exists('mod'.$module->name.'Helper', 'getTitle')) {
                        echo JHtml::_('sliders.panel', call_user_func_array(array('mod'.$module->name.'Helper', 'getTitle'),
                            array($params)), 'cpanel-panel-'.$module->name);
                    }
                    else {
                        echo JHtml::_('sliders.panel', JText::_('MOD_'.$module->name.'_TITLE'), 'cpanel-panel-'.$module->name);
                    }
                    echo $output;
                }

                echo JHtml::_('sliders.end');
            ?>
            <div id="ccc_right_footer">

            </div>
        </div>
    </div>
</div>
<div class="clr"></div>
<hr />
<div style="font-size: small">
    <strong>
        Hotspots <?php echo HOTSPOTS_PRO ? 'Professional' : 'Core' ?> <?php echo HOTSPOTS_VERSION; ?>
    </strong>
    <br />

	<span style="font-size: x-small">
		Copyright &copy;2008&ndash;<?php echo date('Y'); ?> Daniel Dimitrov / compojoom.com
	</span>
	<br />

	<strong>
		Si utiliza puntos de acceso, por favor enviar un comentario a EduGeo
		<?php
			$url = 'http://www.edugeo.com';
			if(HOTSPOTS_PRO) {
				$url = 'http://www.edugeo.com';
			}
		?>
		<a href="<?php echo $url; ?>" target="_blank">Edugeo!!!</a>.
	</strong>
	<br />
	<span style="font-size: x-small">
		EduGeo es software libre publicado bajo la
		<a href="www.gnu.org/licenses/gpl.html">GNU General Public License,</a>
		versión 2 de la licencia o &ndash;a su elección&ndash; cualquier versión posterior 
publicada por la Free Software Foundation.
	</span>
</div>
<div>
	<div class="row-fluid">
		<strong><?php echo JText::_('COM_HOTSPOTS_LATEST_NEWS_PROMOTIONS'); ?>:</strong>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<?php echo JText::_('COM_HOTSPOTS_LIKE_FB'); ?><br/>
			<iframe
				src="//www.edugeo.com"
				scrolling="no" frameborder="0"
				style="border:none; overflow:hidden; width:292px; height:62px;"
				allowTransparency="true"></iframe>
		</div>
		<div class="span3">
			<?php echo JText::_('COM_HOTSPOTS_FOLLOW_TWITTER'); ?><br/><br/>
			<a href="https://twitter.com/" class="twitter-follow-button" data-show-count="false">Follow
				@EduGeo</a>
			<script>!function (d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (!d.getElementById(id)) {
						js = d.createElement(s);
						js.id = id;
						js.src = "//platform.twitter.com/widgets.js";
						fjs.parentNode.insertBefore(js, fjs);
					}
				}(document, "script", "twitter-wjs");</script>
		</div>
	</div>
</div>

<div>
	<?php if (!HOTSPOTS_PRO) : ?>
		<p class="alert alert-warning"><?php echo JText::sprintf('COM_HOTSPOTS_UPGRADE_TO_PRO', 'https://edugeo.com/extension'); ?></p>
	<?php endif; ?>
</div>