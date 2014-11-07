<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class PuntosControllerImport extends HotspotsController
{

    public function import()
    {
       
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $input = JFactory::getApplication()->input;

        $db_max_execution_time = $input->get('db_max_execution_time', 30);
        if ($db_max_execution_time != "30") {
            ini_set('max_execution_time', $db_max_execution_time);
        }

        $db = & JFactory::getDBO();

        $where = '';
        if ($input->get('sobi2published', 0) == 1) {
            $where = " o.published = '1' ";
        }


        if ($input->get('delete_old', 1) == 1) {
            $query = $db->getQuery(true);
            $query->delete('#__puntos_categorie')
                ->where('import_table like "%sobipro%"');
            $db->setQuery($query);
            if (!$db->query()) {
                echo "<script> alert('" . $db->getErrorMsg() . "'); window.history.go (-1); </script>\n";
            }
            $query = $db->getQuery(true);
            $query->delete('#__puntos_marker')
                ->where('import_table like "%sobipro%"');
            $db->setQuery($query);


            try {
                $db->query();
            } catch (JDatabaseException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        $name_fieldid = $input->get('name_fieldid', 1);
        $street_fieldid = $input->get('street_fieldid', 1);
        $zip_fieldid = $input->get('zip_fieldid', 2);
        $town_fieldid = $input->get('town_fieldid', 3);
        $country_fieldid = $input->get('country_fieldid', 6);
        $mail_fieldid = $input->get('mail_fieldid', 7);
        $web_fieldid = $input->get('web_fieldid', 8);
        $phone_fieldid = $input->get('phone_fieldid', 10);
        $description_fieldid = $input->get('description_fieldid', 13);
        $lng_fieldid = $input->get('lng_fieldid', 50);
        $lat_fieldid = $input->get('lat_fieldid', 51);
        $sobi2_link = $input->get('sobi2_link', 1);
        $geomap = $input->get('geomap',0);

        $query = $db->getQuery(true);
        $query->select('c.id AS catid, o.name AS cat_name, c.description, o.state as published')
            ->from('#__sobipro_category AS c')
            ->leftJoin('#__sobipro_object AS o ON c.id = o.id');
        if ($where) {
            $query->where($where);
        }
        $db->setQuery($query);
        $sobiProCats = $db->loadObjectList();

       
        foreach ($sobiProCats as $category) {
            $cats[] = $db->quote($category->cat_name)
                . ',' . $db->quote($category->description)
                . ',' . $db->quote(date('Y-m-d H:i:s'))
                . ',' . $db->quote($category->published)
                . ',' . $db->quote('sobipro')
                . ',' . $db->quote($category->catid);
        }
        $query = $db->getQuery(true);
        $query->insert('#__puntos_categorie')
            ->columns('cat_name, cat_description, cat_date, published, import_table, import_id')
            ->values($cats);
        $db->setQuery($query);

        try {
            $db->query();
        } catch (JDatabaseException $e) {
            echo $e->getMessage();
            return false;
        }

        $query = $db->getQuery(true);
        $query->select('o.*,d.*')
            ->from('#__sobipro_object AS o')
            ->leftJoin('#__sobipro_field_data AS d ON o.id = d.sid')
            ->where('oType = ' . $db->quote('entry'))
            ->where("d.fid IN ($name_fieldid,
                                $street_fieldid,
                                $zip_fieldid,
                                $town_fieldid,
                                $country_fieldid,
                                $mail_fieldid,
                                $web_fieldid,
                                $phone_fieldid,
                                $description_fieldid,
                                $lng_fieldid,
                                $lat_fieldid
                    )");
        if($geomap) {
            $query->select(array('g.latitude', 'g.longitude'))
                ->leftJoin('#__sobipro_field_geo AS g ON o.id = g.sid');
        }
        $db->setQuery($query);
        $spObjects = $db->loadObjectList();

        foreach ($spObjects as $o) {
            if ($o->fid == $name_fieldid) {
                $puntos[$o->id]['id'] = $o->id;
                $puntos[$o->id]['owner'] = $o->owner;
                $puntos[$o->id]['name'] = $o->baseData;
            }
            if ($o->fid == $lng_fieldid) {
                $puntos[$o->id]['lng'] = (float)$o->baseData;
                if($geomap) {
                    if($o->longitude) {
                        $puntos[$o->id]['lng'] = (float)$o->longitude;
                    }
                }

            }
            if ($o->fid == $lat_fieldid) {
                $puntos[$o->id]['lat'] = (float)$o->baseData;
                if($geomap) {
                    if($o->latitude) {
                        $puntos[$o->id]['lat'] = (float)$o->latitude;
                    }
                }
            }

            if ($o->fid == $street_fieldid) {
                $puntos[$o->id]['street'] = $o->baseData;
            }
            if ($o->fid == $zip_fieldid) {
                $puntos[$o->id]['plz'] = $o->baseData;
            }
            if ($o->fid == $town_fieldid) {
                $puntos[$o->id]['town'] = $o->baseData;
            }
            if ($o->fid == $country_fieldid) {
                $puntos[$o->id]['country'] = $o->baseData;
            }
            if ($o->fid == $mail_fieldid) {
                $puntos[$o->id]['mail'] = $o->baseData;
            }
            if ($o->fid == $web_fieldid) {
             
                $data = base64_decode($o->baseData, true);
                if($data) {
                    $data = unserialize(base64_decode($o->baseData));
                    $puntos[$o->id]['web'] = $data['protocol'] . '://' . $data['url'];
                } else {
                    $puntos[$o->id]['web'] = $o->baseData;
                }
            }
            if ($o->fid == $phone_fieldid) {
                $puntos[$o->id]['phone'] = $o->baseData;
            }
            if ($o->fid == $description_fieldid) {
                $puntos[$o->id]['description'] = $o->baseData;
            }
        }

        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__sobipro_object AS o')
            ->leftJoin('#__sobipro_field_option_selected AS s ON o.id = s.sid')
            ->where('oType = ' . $db->quote('entry'))
            ->where('s.fid = ' . $db->quote($country_fieldid));
        $db->setQuery($query);
        $spObjects = $db->loadObjectList();

        foreach ($spObjects as $o) {
            $puntos[$o->id]['country'] = $o->optValue;
        }

        $query = $db->getQuery(true);
        $query->select('o.id, o.nid, r.*')
            ->from('#__sobipro_object AS o')
            ->leftJoin('#__sobipro_relations AS r ON o.id = r.id')
            ->where('o.oType = ' .$db->quote('entry'))
            ->group('o.id');
        $db->setQuery($query);
        $spObjects = $db->loadObjectList();

        foreach ($spObjects as $o) {
            if (isset($puntos[$o->id])) {
                $puntos[$o->id]['catid'] = $o->pid;
            }

        }

        $query = $db->getQuery(true);
        $query->select('id, import_id')
            ->from('#__puntos_categorie')
            ->where('import_table LIKE "%sobipro%"');

        $db->setQuery($query);
        $cats = $db->loadObjectList('import_id');


        $hInsert = array();
        foreach ($puntos as $value) {

            if (!isset($value['street'])) {
                $value['street'] = '';
            }

            if ($value['phone']) {
                $descriptionSmall[] = $value['phone'] . '<br />';
            }
            if ($value['mail']) {
                $descriptionSmall[] = '<a href="mailto:' . $value['mail'] . '">' . $value['mail'] . '</a><br />';
            }

            $params['sticky'] = 0;
            if($sobi2_link) {
                $params['link_to'] = 'sobipro';
                $params['link_to_id'] = $value['id'];

            }

            if (!isset($value['lat'])) {
                $value['lat'] = 0;
            }
            if (!isset($value['lng'])) {
                $value['lng'] = 0;
            }
            $hInsert[] = $db->quote($value['name'])
                . ',' . $db->quote($value['owner'])
                . ',' . $db->quote(date('Y-m-d H:i:s'))
                . ',' . $db->quote($cats[$value['catid']]->id)
                . ',' . $db->quote($value['plz'])
                . ',' . $db->quote($value['town'])
                . ',' . $db->quote($value['street'])
                . ',' . $db->quote($value['country'])
                . ',' . $db->quote($value['lat'])
                . ',' . $db->quote($value['lng'])
	            . ',' . $db->quote(1)
                . ',' . $db->quote(implode('', $descriptionSmall) . $value['description'])
                . ',' . $db->quote(json_encode($params))
                . ',' . $db->quote('sobipro')
                . ',' . $db->quote($value['id']);
        }

        $query = $db->getQuery(true);
        $query->insert('#__puntos_marker')
            ->columns('name,created_by,created, catid, plz, town, street,
            country, gmlat, gmlng, access, description_small, params, import_table, import_id')
            ->values($hInsert);
        $db->setQuery($query);

        try {
            $db->execute();
        } catch (JDatabaseException $e) {
            echo $e->getMessage();
            return false;
        }

        $this->setRedirect('index.php?option=com_puntos&view=puntos', JText::_('COM_PUNTOS_SOBIPRO_SUCCESSFULL_IMPORT'));
    }

}