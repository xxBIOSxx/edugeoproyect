<?php


defined('_JEXEC') or die;

require_once dirname(__FILE__).'/helper.php';

$cacheDir = JPATH_CACHE;
if (!is_writable($cacheDir))
{
    echo '<div>';
    echo JText::_('MOD_FEED_ERR_CACHE');
    echo '</div>';
    return;
}

$rssurl	= $params->get('feedurl', '');

if (empty ($rssurl))
{
    echo '<div>';
    echo JText::_('MOD_FEED_ERR_NO_URL');
    echo '</div>';
    return;
}

require JModuleHelper::getLayoutPath('mod_ccc_puntos_newsfeed', $params->get('layout', 'default'));