<?php

defined('_JEXEC') or die();

require_once dirname(__FILE__).'/config.php';

class EdugeoControlCenter {

    public static $version = '1.0';

   
    private static function loadLanguage()
    {
        // Load translations
        $basePath = dirname(__FILE__);
        $jlang = JFactory::getLanguage();
        $jlang->load('edugeocontrolcenter', $basePath, 'es-ES', true); /
        $jlang->load('edugeocontrolcenter', $basePath, $jlang->getDefault(), true); 
        $jlang->load('edugeocontrolcenter', $basePath, null, true); 
    }

  
    public static function handleRequest($task = 'overview')
    {
      
        self::loadLanguage();

        if($task == 'overview'){
            
            require_once dirname(__FILE__).'/classes/controller.php';
            $controller = new ControlCenterController();
            $controller->execute(JRequest::getCmd('task','overview'));
            $controller->redirect();
        } else {
            JRequest::setVar('task', $task);
         
            require_once dirname(__FILE__).'/classes/controller.php';
            $controller = new ControlCenterController();
            $controller->execute($task);
            $controller->redirect();
        }

    }



}