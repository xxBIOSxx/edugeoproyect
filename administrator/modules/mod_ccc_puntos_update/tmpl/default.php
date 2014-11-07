<?php


defined('_JEXEC') or die('Restricted access');

$extension = $params->get('extension', '');

require_once (JPATH_ADMINISTRATOR . "/components/" . $extension . "/liveupdate/liveupdate.php");

$updateinfos = LiveUpdate::getUpdateInformation();

?>
<div style="padding: 12px;">
    <?php
        if($updateinfos->hasUpdates) {
            echo "<h2>" . JText::_('MOD_CCC_PUNTOS_UPDATE_UPDATE_FOUND') . "</h2>";
            echo "<p>";
                echo JText::_('MOD_CCC_PUNTOS_UPDATE_NEW_VERSION') . ": " . $updateinfos->version . "<br />";
                echo JText::_('MOD_CCC_PUNTOS_UPDATE_NEW_VERSION_DATE') . ": " . $updateinfos->date . "<br />";
            echo "</p>";
            echo "<p>";
                echo JText::_('MOD_CCC_PUNTOS_UPDATE_HOWTO_UPDATE_TEXT');
            echo "</p>";
        } else {
            echo JText::_('MOD_CCC_PUNTOS_UPDATE_NO_UPDATES');
        }
    ?>
</div>