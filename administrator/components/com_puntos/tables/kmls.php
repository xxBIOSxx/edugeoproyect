<?php



defined( '_JEXEC' ) or die ( 'Restricted access' );

class TableKmls extends JTable
{
	
	public function __construct(&$db)
	{
		parent::__construct( '#__puntos_kmls', 'puntos_kml_id', $db );
	}

    public function store($updateNulls=false) {
        $date = JFactory::getDate();
        $user = JFactory::getUser();
        if (!$this->puntos_kml_id) {
            if (!intval($this->created)) {
                $this->created = $date->toSql();
            }
            if (empty($this->created_by)) {
                $this->created_by = $user->get('id');
            }
        }

        return parent::store($updateNulls);
    }

	
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		
		$k = $this->_tbl_key;

	
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state = (int) $state;

		
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			
			else
			{
				$e = new JException(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				$this->setError($e);

				return false;
			}
		}

		$query = $this->_db->getQuery(true);
		$query->update($this->_tbl);
		$query->set('state = ' . (int) $state);

		if (property_exists($this, 'checked_out') || property_exists($this, 'checked_out_time'))
		{
			$query->where('(checked_out = 0 OR checked_out = ' . (int) $userId . ')');
			$checkin = true;
		}
		else
		{
			$checkin = false;
		}

		$query->where($k . ' = ' . implode(' OR ' . $k . ' = ', $pks));

		$this->_db->setQuery($query);

		if (!$this->_db->query())
		{
			$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_PUBLISH_FAILED', get_class($this), $this->_db->getErrorMsg()));
			$this->setError($e);

			return false;
		}

		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			foreach ($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		if (in_array($this->$k, $pks))
		{
			$this->published = $state;
		}

		$this->setError('');
		return true;
	}
}