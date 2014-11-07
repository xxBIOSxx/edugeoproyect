<?php


defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ADMINISTRATOR.'/components/com_puntos/helpers/puntos.php');
$version = PuntosHelper::getSettings('version')

?>
<div style="padding: 12px;">
	<div style="float:right;margin:10px;">
		<?php
		echo JHTML::_('image', JURI::root() . 'media/com_puntos/images/utils/logo.jpg', 'edugeo.com', 'style="width:200px;"');
		?>
	</div>

	<div>
		<h1><?php echo JText::_('COM_PUNTOS_INFORMATIONS'); ?></h1>

		<h3><?php echo JText::_('COM_PUNTOS_VERSION'); ?></h3>
		<p><?php echo $version; ?></p>

		<h3><?php echo JText::_('COM_PUNTOS_COPYRIGHT'); ?></h3>
		<p>Copyrigth &copy; <?php echo date("Y"); ?> EduGeo.com</p>

		<h3><?php echo JText::_('COM_PUNTOS_LICENSE'); ?></h3>
		<p><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a></p>
		<br />


		<h3>Thank you</h3>
		<div>
		Este software no habría sido posible sin la ayuda de los mencionados aquí. 
GRACIAS por su ayuda continua, apoyo e inspiración!
		</div>
		<ul>
			<li>
				<em>Javier Sandoval </em> (<a href="http://www.sandovalbozo.com/" target="_blank">http://www.sandovalbozo.com/</a> && <a href="http://www.akeebabackup.com/" target="_blank">http://www.akeebabackup.com/</a>) <br />
				estamos para ayudarlos
			</li>
						
		
		

		</ul>


		<h3><?php echo JText::_('COM_PUNTOS_HELP'); ?></h3>
		<p>
			<a href="http://www.edugeo.com/">Sitio de edugeo</a><br />
		</p>
		<p><br />
			Maps are created by Google Maps&trade;<br />
			<br />
			Google&trade; is a trademark of Google Inc.<br />
			Google Maps&trade; is a trademark of Google Inc.<br /><br />
			Sigsiu Online Business Index 2 (SOBI2) is developed by Sigsiu.NET
		</p>
	</div>
</div>