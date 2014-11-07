<?php

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/helper.php';

$list = ModPuntosHelper::getList($params);

require JModuleHelper::getLayoutPath('mod_puntos_list', $params->get('layout', 'default'));
