<?php


defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');


class PuntosModelPunto extends JModelAdmin
{
	private $Punto = null;

	private $id = null;

	private $Puntos = null;


	public function __construct($config = array())
	{
		$config['event_before_save'] = 'onBeforePuntoSave';
		$config['event_after_save'] = 'onAfterPuntoSave';

		parent::__construct($config);

		$input = JFactory::getApplication()->input;
		$id = $input->getInt('id', 0);
		$this->_catid = $input->getInt('cat', 1);

		if ($id != 0)
		{
			$this->id = $id;
		}
	}


	public function getPunto()
	{
		if (!$this->Punto)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select(' m.id as Puntos_id, m.*, u.name AS user_name')
				->from($db->qn('#__Puntos_marker') . ' AS m')
				->leftJoin('#__users AS u ON u.id = m.created_by')
				->where('m.id = ' . $db->Quote($this->id));
			$db->setQuery($query, 0, 1);
			$this->Punto = $db->loadObject();

			if (!$this->Punto->published)
			{
				JError::raiseError(404, "Invalid ID provided");
			}

			if ($this->Punto)
			{
				$query = 'SELECT * FROM ' . $db->qn('#__Puntos_categorie')
					. ' WHERE id = ' . $db->Quote($this->Punto->catid);
				$db->setQuery($query, 0, 1);
				$this->Punto->category = $db->loadObject();
			}
		}

		return $this->Punto;
	}


	public function getUserPuntos($userId)
	{
		$db = JFActory::getDBO();
		$query = 'SELECT m.*, c.cat_name FROM ' . $db->qn('#__Puntos_marker') . ' AS m'
			. ' LEFT JOIN ' . $db->qn('#__Puntos_categorie') . ' AS c'
			. ' ON m.catid = c.id'
			. ' WHERE m.created_by = ' . $db->Quote($userId)
			. ' AND m.published = 1'
			. ' ORDER BY m.' . PuntosHelper::getSettings('Puntos_order', 'name ASC');
		$db->setQuery($query);

		return $db->loadObjectList();
	}


	public function search($sentence, $offset = null, $limit = null)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$data = array();

		$query->select('SQL_CALC_FOUND_ROWS m.id AS Puntos_id, m.*,c.*, m.params as params, c.params as cat_params, u.name AS user_name')
			->from($db->quoteName('#__Puntos_marker') . 'AS m')
			->leftJoin($db->quoteName('#__Puntos_categorie') . 'AS c ON  m.catid = c.id')
			->leftJoin('#__users AS u ON u.id = m.created_by')
			->order('m.' . PuntosHelper::getSettings('Puntos_order', 'name ASC'));

		$this->buildWhereCoordQuery($query);

		$this->buildWhereSearchQuery($sentence, $query);

		$this->buildWhereGeneralQuery($query);

		$db->setQuery($query, $offset, $limit);
		$data['Puntos'] = $db->loadObjectList();

		$db->setQuery('SELECT FOUND_ROWS()');
		$data['count'] = $db->loadResult();

		$query->clear('select');
		$query->select('COUNT(*) AS count');
		$query->clear('where');
		$this->buildWhereSearchQuery($sentence, $query);
		$this->buildWhereGeneralQuery($query);

		$db->setQuery($query);

		$data['worldCount'] = $db->loadObject()->count;

		return $data;
	}


	protected function populateState()
	{
		$app = JFactory::getApplication();
		$this->setState('filter.language', $app->getLanguageFilter());
	}

	
	public function buildWhereGeneralQuery(&$query)
	{
		$input = JFactory::getApplication()->input;

		$query->where(' m.published = 1');
		$query->where(' c.published = 1');

		$nullDate = $query->nullDate();
		$nowDate = $query->Quote(JFactory::getDate()->toSQL());

		$query->where('(m.publish_up = ' . $nullDate . ' OR m.publish_up <= ' . $nowDate . ')');
		$query->where('(m.publish_down = ' . $nullDate . ' OR m.publish_down >= ' . $nowDate . ')');

		if ($this->getState('filter.language'))
		{
			$query->where('m.language in (' . $query->quote($input->getString('hs-language')) . ',' . $query->quote('*') . ')');
		}
	}

	
	public function buildWhereSearchQuery($sentence, &$q)
	{
		$name = $q->quoteName('m.name');
		$description = $q->quoteName('m.description');
		$descriptionSmall = $q->quoteName('m.description_small');
		$plz = $q->quoteName('m.plz');
		$catName = $q->quoteName('c.cat_name');
		$street = $q->quoteName('m.street');
		$country = $q->quoteName('m.country');
		$town = $q->quoteName('m.town');
		$and = array();

		if (preg_match('/"([^"]+)"/', $sentence, $m))
		{
			
			$searchWord = $q->Quote('%' . $q->escape(trim($m[1]), true) . '%', false);

			$search[] = $name . ' LIKE ' . $searchWord;
			$search[] = $description . ' LIKE ' . $searchWord;
			$search[] = $descriptionSmall . ' LIKE ' . $searchWord;
			$search[] = $plz . ' LIKE ' . $searchWord;
			$search[] = $catName . ' LIKE ' . $searchWord;
			$search[] = $street . ' LIKE ' . $searchWord;
			$search[] = $country . ' LIKE ' . $searchWord;
			$search[] = $town . ' LIKE ' . $searchWord;

			$word = trim(str_replace('"' . $m[1] . '"', '', $sentence));

			if ($word)
			{
				$searchWord = $q->Quote('%' . $q->escape(trim($word), true) . '%', false);
				$and[] = $name . ' LIKE ' . $searchWord;
				$and[] = $description . ' LIKE ' . $searchWord;
				$and[] = $descriptionSmall . ' LIKE ' . $searchWord;
				$and[] = $plz . ' LIKE ' . $searchWord;
				$and[] = $catName . ' LIKE ' . $searchWord;
				$and[] = $street . ' LIKE ' . $searchWord;
				$and[] = $country . ' LIKE ' . $searchWord;
				$and[] = $town . ' LIKE ' . $searchWord;
			}
		}
		else
		{
			$words = explode(' ', $sentence);

			foreach ($words as $word)
			{
				$searchWord = $q->Quote('%' . $q->escape($word, true) . '%', false);
				$search[] = $name . ' LIKE ' . $searchWord;
				$search[] = $description . ' LIKE ' . $searchWord;
				$search[] = $descriptionSmall . ' LIKE ' . $searchWord;
				$search[] = $plz . ' LIKE ' . $searchWord;
				$search[] = $catName . ' LIKE ' . $searchWord;
				$search[] = $street . ' LIKE ' . $searchWord;
				$search[] = $country . ' LIKE ' . $searchWord;
				$search[] = $town . ' LIKE ' . $searchWord;
			}
		}

		$q->where('(' . implode(' OR ', $search) . ')');

		if (count($and))
		{
			$q->where('(' . implode(' OR ', $and) . ')');
		}
	}

	
	public function buildWhereCoordQuery(&$query)
	{
		$input = JFactory::getApplication()->input;
		$level = $input->get('z', 0);

		$levels = array(0, 1);
		
		if (!in_array($level, $levels))
		{
			$ne = $input->getString('ne', '');
			$sw = $input->getString('sw', '');
			list($nelat, $nelng) = explode(',', $ne);
			list($swlat, $swlng) = explode(',', $sw);

			
			if ($nelng > $swlng)
			{
				$query->where(' (m.gmlng > ' . $swlng . ' AND m.gmlng < ' . $nelng . ')');
				$query->where(' (m.gmlat <= ' . $nelat . ' AND m.gmlat >= ' . $swlat . ')');
			}
			else
			{
				$query->where(' (m.gmlng >= ' . $swlng . ' OR m.gmlng <= ' . $nelng . ')');
				$query->where(' (m.gmlat <= ' . $nelat . ' AND m.gmlat >= ' . $swlat . ')');
			}
		}

		return $query;
	}

	
	public function getTable($type = 'Marker', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_Puntos.marker', 'marker', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		if ($this->getState('Punto.id'))
		{
			$form->setFieldAttribute('catid', 'action', 'core.edit');
		}
		else
		{
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		return $form;
	}


	public function validate($form, $data, $group = null)
	{
		$user = JFactory::getUser();
		$jform = JFactory::getApplication()->input->get('jform', array(), 'ARRAY');

		if (!$user->id)
		{
			$data['created_by_alias'] = $jform['created_by_alias'];
			$data['email'] = $jform['email'];
		}
		else
		{
			$data['created_by_alias'] = $user->name;
			$data['created_by'] = $user->id;
		}

		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];

		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$data['created_by_ip'] = ip2long($ip);

		return parent::validate($form, $data);
	}


	public function save($data)
	{
		$dispatcher = JDispatcher::getInstance();
		$table = $this->getTable();
		$key = $table->getKeyName();
		$pk = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
		$isNew = true;

		JPluginHelper::importPlugin('Puntos');

		$file = JRequest::getVar('jform', '', 'files', 'array');
		$emptyFile = true;

		if (!empty($file))
		{
			if (!empty($file['name']['picture']))
			{
				foreach ($file as $key => $value)
				{
					$newFile[$key] = $value['picture'];
				}

				$emptyFile = false;
			}
		}

		if (!$emptyFile)
		{
			$picture = PuntosUtils::uploadPicture($newFile);

			if ($picture)
			{
				$data['picture'] = $picture;
				PuntosUtils::createThumb($picture);
				$data['picture_thumb'] = "thumb_" . $picture;
			}
		}



		try
		{
			if ($pk > 0)
			{
				$table->load($pk);
				$isNew = false;
			}

			if (!$table->bind($data))
			{
				$this->setError($table->getError());

				return false;
			}

			$this->prepareTable($table);

			if (!$table->check())
			{
				$this->setError($table->getError());

				return false;
			}

			$result = $dispatcher->trigger($this->event_before_save, array($this->option . '.' . $this->name, &$table, $isNew));

			if (in_array(false, $result, true))
			{
				$this->setError($table->getError());

				return false;
			}

			if (!$table->store())
			{
				$this->setError($table->getError());

				return false;
			}

			$this->cleanCache();

			$dispatcher->trigger($this->event_after_save, array($this->option . '.' . $this->name, &$table, $isNew));
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		$pkName = $table->getKeyName();

		if (isset($table->$pkName))
		{
			$this->setState($this->getName() . '.id', $table->$pkName);
		}

		$this->setState($this->getName() . '.new', $isNew);

		return true;
	}
}
