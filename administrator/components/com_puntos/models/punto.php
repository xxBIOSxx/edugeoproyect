<?php



defined('_JEXEC') or die();
jimport('joomla.application.component.modeladmin');


class PuntosModelPunto extends JModelAdmin
{

	
	protected $text_prefix = 'COM_PUNTOS';

	
	protected function prepareTable($table)
	{
		
		if ($table->published == 1 && intval($table->publish_up) == 0)
		{
			$table->publish_up = JFactory::getDate()->toSql();
		}

	}


	public function getTable($type = 'Marker', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	
	public function getForm($data = array(), $loadData = true)
	{
	
		$app = JFactory::getApplication();

	
		$form = $this->loadForm('com_puntos.marker', 'marker', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		if ($this->getState('punto.id'))
		{
			$form->setFieldAttribute('catid', 'action', 'core.edit');
		}
		else
		{
	
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

	
		if (!$this->canEditState((object) $data))
		{
			
			$form->setFieldAttribute('published', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');

	
			$form->setFieldAttribute('published', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
		}

		return $form;
	}


	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			$item->puntoText = trim($item->description) != '' ?
				$item->description_small . "<hr id=\"system-readmore\" />" . $item->description : $item->description_small;
		}

		return $item;
	}

	
	protected function loadFormData()
	{
	
		$data = JFactory::getApplication()->getUserState('com_puntos.edit.punto.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	public function save($data)
	{
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

		return parent::save($data);
	}
}
