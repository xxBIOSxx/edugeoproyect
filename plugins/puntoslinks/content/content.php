<?php

defined('_JEXEC') or die('Restricted access');


class plgpuntoslinksContent extends JPlugin
{
    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject, $config);
        $this->loadLanguage('plg_puntoslinks_content.sys');
    }

    public function onCreateLink($id) {
        $link = '';
        $route = JPATH_ROOT . '/components/com_content/helpers/route.php';
        if(file_exists($route)) {
            require_once($route);
            if($id) {
                $link = JRoute::_(ContentHelperRoute::getArticleRoute($id, $this->getCatId($id)));
            }
        }
        return $link;
    }

    private function getCatId($contentId) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('catid')->from('#__content')->where('id='.$db->quote($contentId));
        $db->setQuery($query);
        $cat = $db->loadObject();
        if(is_object($cat)) {
            return $cat->catid;
        }
        return 0;
    }
}