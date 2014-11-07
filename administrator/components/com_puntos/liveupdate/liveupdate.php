<?php


defined('_JEXEC') or die();

require_once dirname(__FILE__).'/classes/abstractconfig.php';
require_once dirname(__FILE__).'/config.php';

class LiveUpdate
{
	
	public static $version = '1.1';

	
	private static function loadLanguage()
	{
		
		$basePath = dirname(__FILE__);
		$jlang = JFactory::getLanguage();
		$jlang->load('liveupdate', $basePath, 'en-GB', true); 
		$jlang->load('liveupdate', $basePath, $jlang->getDefault(), true); 
		$jlang->load('liveupdate', $basePath, null, true); 
	}

	
	public static function handleRequest()
	{
		
		self::loadLanguage();

		
		require_once dirname(__FILE__).'/classes/controller.php';
		$controller = new LiveUpdateController();
		$controller->execute(JRequest::getCmd('task','overview'));
		$controller->redirect();
	}

	
	public static function getUpdateInformation($force = false)
	{
		require_once dirname(__FILE__).'/classes/updatefetch.php';
		$update = new LiveUpdateFetch();
		$info = $update->getUpdateInformation($force);
		$hasUpdates = $update->hasUpdates();
		$info->hasUpdates = $hasUpdates;

		$config = LiveUpdateConfig::getInstance();
		$extInfo = $config->getExtensionInformation();

		$info->extInfo = (object)$extInfo;

		return $info;
	}

	public static function getIcon($config=array())
	{
        
        self::loadLanguage();

        
        $button = array();

        $defaultConfig = array(
            'option'			=> JRequest::getCmd('option',''),
            'view'				=> 'liveupdate',
            'mediaurl'			=> JURI::base().'components/'.JRequest::getCmd('option','').'/liveupdate/assets/'
        );
        $c = array_merge($defaultConfig, $config);

        $button['link'] = 'index.php?option='.$c['option'].'&view='.$c['view'];
        $button['image'] = $c['mediaurl'];

        $updateInfo = self::getUpdateInformation();
        if(!$updateInfo->supported) {
         
            $button['class'] = 'liveupdate-icon-notsupported';
            $button['image'] .= 'nosupport-32.png';
            $button['text'] = JText::_('LIVEUPDATE_ICON_UNSUPPORTED');
        } elseif($updateInfo->stuck) {
           
            $button['class'] = 'liveupdate-icon-crashed';
            $button['image'] .= 'nosupport-32.png';
            $button['text'] = JText::_('LIVEUPDATE_ICON_CRASHED');
        } elseif($updateInfo->hasUpdates) {
            
            $button['class'] = 'liveupdate-icon-updates';
            $button['image'] .= 'update-32.png';
            $button['text'] = JText::_('LIVEUPDATE_ICON_UPDATES');
        } else {
          
            $button['class'] = 'liveupdate-icon-noupdates';
            $button['image'] .= 'current-32.png';
            $button['text'] = JText::_('LIVEUPDATE_ICON_CURRENT');
        }
        if(version_compare(JVERSION, '2.5', 'ge')) {
            return '<div class="icon"><a href="'.$button['link'].'">'.
                '<div style="text-align: center;"><img src="'.$button['image'].'" width="32" height="32" border="0" align="middle" style="float: none" /></div>'.
                '<span class="'.$button['class'].'">'.$button['text'].'</span></a></div>';
        } else {
            return '<div class="icon"><a href="'.$button['link'].'">'.
                '<div><img src="'.$button['image'].'" width="32" height="32" border="0" align="middle" style="float: none" /></div>'.
                '<span class="'.$button['class'].'">'.$button['text'].'</span></a></div>';
        }
	}
}
