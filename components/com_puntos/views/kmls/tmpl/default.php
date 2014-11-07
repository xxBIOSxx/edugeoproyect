<?php

defined('_JEXEC') or die('Restricted access');

$json = array();
foreach((array)$this->kmls as $key => $kml) {
    $json[$kml->catid][] = array(
        'file' => JURI::root() .'media/com_puntos/kmls/'.$kml->mangled_filename
    );
}

echo json_encode($json);

jexit();