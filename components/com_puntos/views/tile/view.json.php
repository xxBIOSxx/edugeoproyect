<?php


defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_SITE . '/views/json.php');

class PuntosViewTile extends PuntosJson
{
    public function display($tpl = null) {
        $tile = $this->getModel();
        $this->items = $tile->getItems(false);

        parent::display();
    }
}