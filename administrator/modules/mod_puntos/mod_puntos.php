<?php


defined('_JEXEC') or die;


require_once dirname(__FILE__).'/helper.php';
JLoader::register('puntosHelper', JPATH_ROOT . '/administrator/components/com_puntos/helpers/puntos.php');
JLoader::register('puntosUtils', JPATH_ROOT . '/components/com_puntos/utils.php');
JLoader::register('puntosHelperRoute', JPATH_ROOT . '/components/com_puntos/helpers/route.php');



require JModuleHelper::getLayoutPath('mod_puntos', $params->get('layout', 'default'));