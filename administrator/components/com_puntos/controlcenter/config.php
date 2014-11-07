<?php


defined('_JEXEC') or die('Restricted access');


class ControlCenterConfig {

    var $version                = "2.0.0";
    var $copyright              = "Copyright (C) 2014 EduGeo.com";
    var $license                = "GPL v2 or later";
    var $translation            = "Español: EduGeo.com <br />";
    var $description            = "COM_PUNTOS_XML_DESCRIPTION";
    var $thankyou               = "";

    var $_extensionTitle        = "com_puntos";
    var $extensionPosition     = "puntos"; 

    var $_logopath              = '/media/com_matukio/backend/images/logo.png';

    public static function &getInstance()
    {
        static $instance = null;

        if(!is_object($instance)) {
            $instance = new ControlCenterConfig();
        }

        return $instance;
    }
}