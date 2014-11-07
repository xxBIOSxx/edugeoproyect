<?php

defined('_JEXEC') or die('Restricted access');

class TableMarker extends JTable {

	public function __construct(&$db) {
		parent::__construct('#__puntos_marker', 'id', $db);
	}

	public function store($updateNulls=false) {
        $date = JFactory::getDate();
        $user = JFactory::getUser();
        if ($this->id) {
          
            $this->modified     = $date->toSql();
            $this->modified_by  = $user->get('id');
        } else {
       
            if (!intval($this->created)) {
                $this->created = $date->toSql();
            }
            if (empty($this->created_by)) {
                $this->created_by = $user->get('id');
            }
        }

        if(parent::store($updateNulls)) {
            $this->countCategoryMarker($this->catid);
            return true;
        }

        return false;
	}


   
    public function bind($array, $ignore = '')
    {
        
        if (isset($array['puntoText']))
        {
            $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
            $tagPos = preg_match($pattern, $array['puntoText']);

            if ($tagPos == 0)
            {
                $this->description_small = $array['puntoText'];
                $this->description = '';
            }
            else
            {
                list ($this->description_small, $this->description) = preg_split($pattern, $array['puntoText'], 2);
            }
        }

        if (isset($array['params']) && is_array($array['params'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['params']);
            $array['params'] = (string)$registry;
        }

     
        if (isset($array['rules']) && is_array($array['rules']))
        {
            $rules = new JAccessRules($array['rules']);
            $this->setRules($rules);
        }

        return parent::bind($array, $ignore);
    }

	
	public function publish( $cid=null, $publish=1, $user_id=0 )
	{

        if(parent::publish($cid,$publish,$user_id)) {
            $this->countCategoryMarker($cid);
            return true;
        }

        return false;
	}

    public function check()
    {

		if ($this->publish_down > $this->_db->getNullDate() && $this->publish_down < $this->publish_up)
		{
			$temp = $this->publish_up;
			$this->publish_up = $this->publish_down;
			$this->publish_down = $temp;
		}
        return true;
    }

	
	public function countCategoryMarker($ids) {
		$db = $this->_db;
		$query = $db->getQuery(true);
		if(is_array($ids)) {
			$query->select('DISTINCT(catid)')->from('#__puntos_marker')
				->where('id IN ('.implode(',', $ids).')');
			$db->setQuery($query);
			$ids = $db->loadColumn();
		} else {
			$ids = array($ids);
		}

		foreach($ids as $value) {
			$query->clear();
			$query->select('COUNT(*)')->from($db->qn('#__puntos_marker'))
				->where('catid = ' . $db->q($value))
				->where('published = ' . $db->q(1));
			$db->setQuery($query);
			$count = $db->loadRow();

			$query->clear();
			$query->update('#__puntos_categorie')->set( 'count = ' . $db->q($count[0]) )
					->where('id =' . $db->q($value) );

			$db->setQuery($query);
			$db->execute();
		}

	}

   
    protected function _getAssetName()
    {
        $k = $this->_tbl_key;
        return 'com_puntos.marker.'.(int) $this->$k;
    }


	
    protected function _getAssetParentId($table = null, $id = null)
    {
        $asset = JTable::getInstance('Asset');
        $asset->loadByName('com_puntos');
        return $asset->id;
    }

}
