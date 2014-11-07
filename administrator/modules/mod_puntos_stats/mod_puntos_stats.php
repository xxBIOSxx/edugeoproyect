<?php

defined('_JEXEC') or die;


require_once dirname(__FILE__).'/helper.php';
JLoader::register('PuntosHelper', JPATH_ROOT . '/administrator/components/com_puntos/helpers/puntos.php');
JLoader::register('puntosUtils', JPATH_ROOT . '/components/com_puntos/utils.php');
JLoader::register('PuntosHelperRoute', JPATH_ROOT . '/components/com_puntos/helpers/route.php');


require JModuleHelper::getLayoutPath('mod_puntos_stats', $params->get('layout', 'default'));