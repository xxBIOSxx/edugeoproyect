<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class PuntosView extends JViewLegacy {
	

	public function includeJavascript(){
		jimport('joomla.filesystem.path');
		jimport('joomla.filesystem.file');
		
		$fileToFind = 'init.js';
		
		$path = JPATH_BASE . '/media/com_puntos/js/views/' . $this->getName() . '/init.js';
		$uri = 'media/com_puntos/js/views/' . $this->getName() . '/init.js';
		
		if($jsfile = JPath::find($this->_path['template'], $fileToFind)) {
			$path = $jsfile;
			
			if ($pos = strpos($jsfile, 'templates')) {
				$uri = str_replace('\\', '/', substr($jsfile,$pos));;
			}
		}
		
		if(JFile::exists($path)) {
			$document = JFactory::getDocument();
			
			$script = JFile::read($path);
			$document->addScriptDeclaration($script);
		}
	}
	
	public function includeMootoolsMore() {
		JHtmlBehavior::framework(true);
	}
	
	public function setMootoolsLocale() {
		$document = JFactory::getDocument();
		$language = JFactory::getLanguage();

		$mootoolsLocale = "Locale.use('".$language->getTag()."')";

		$locale = "window.addEvent('domready', function() {
			$mootoolsLocale
		});";
		
		$document->addScriptDeclaration($locale);
	}
}

