<?php


defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerform');
jimport('joomla.filesystem.file');


class PuntosControllerCategory extends JControllerForm
{
	private $blacklist = array(".php",
		".phtml",
		".php3",
		".php4",
		".php5",
		".html",
		".txt",
		".dhtml",
		".htm",
		".doc",
		".asp",
		".net",
		".js",
		".rtf"
	);

	
	public function __construct()
	{
		parent::__construct();

	
		$this->registerTask('add', 'edit');
		$this->registerTask('apply', 'save');
	}

	
	public function save($key = null, $urlVar = null)
	{
		$db = JFactory::getDBO();
		$row = JTable::getInstance('categorie', 'Table');
		$input = JFactory::getApplication()->input;

		$id = $input->getInt('id', 0);

		$selectIcon = $input->getString('select-icon');
		$data = $input->get('jform', array(), 'array');

		if (!$row->bind($data))
		{
			echo "<script> alert('" . $row->getError() . "'); window.history.go (-1); </script>\n";
			exit();
		}

		if (!isset($row->cat_date))
		{
			$row->cat_date = date('Y-m-d H:i:s');
		}

		switch ($selectIcon)
		{
			case 'new':
				$name = $this->uploadFile('cat_icon');

				if ($name !== false)
				{
					$row->cat_icon = $name;
				}
				break;
			case 'delete':
				$this->deleteImage('cat_icon', $id);
				break;
			case 'sample':
				$name = $this->copySampleImage($data['wsampleicon']);

				if ($name !== false)
				{
					$row->cat_icon = $name;
				}
				break;
		}

		$catImage = $this->uploadFile('cat_image');

		if ($catImage !== false)
		{
			$row->cat_image = $catImage;
		}

		if ($row->id)
		{
			$query = 'SELECT COUNT(*) AS count FROM ' . $db->quoteName('#__puntos_marker') . ' WHERE catid = ' . $row->id;
			$db->setQuery($query);
			$row->count = $db->loadObject()->count;
		}

		if (!$row->store())
		{
			echo "<script> alert('" . $row->getError() . "'); window.history.go (-1); </script>\n";
			exit();
		}


		switch ($this->task)
		{
			case 'apply':
				$msg = JText::_('COM_PUNTOS_CATAPPLY');
				$link = 'index.php?option=com_puntos&task=category.edit&id=' . $row->id;
				break;
			case 'save':
			default:
				$msg = JText::_('COM_PUNTOS_CATSAVE');
				$link = 'index.php?option=com_puntos&view=categories';
				break;
		}

		$this->setRedirect($link, $msg);
	}

	
	private function copySampleImage($image)
	{
		$appl = JFactory::getApplication();
		$user = JFactory::getUser();
		$sampleImagePath = JPATH_ROOT . '/media/com_puntos/images/categories/' . $image;
		$newImageName = time() . '_' . JString::substr($user->name, 0, 1) . '_' . preg_replace('#(sample|_shadow|\/)#', '', $image);

		$moveTo = JPATH_ROOT . '/media/com_puntos/images/categories/' . $newImageName;

		if (JFile::copy($sampleImagePath, $moveTo))
		{
			$msg = JText::sprintf('COM_PUNTOS_SAMPLE_IMAGE_COPIED', $newImageName);
			$appl->enqueueMessage($msg);

			return $newImageName;
		}
		else
		{
			$msg = JText::sprintf('COM_PUNTOS_SAMPLE_IMAGED_COPY_FAILED', $image);
		}

		$appl->enqueueMessage($msg);

		return false;
	}

	
	private function deleteImage($column, $id)
	{
		$db = JFactory::getDBO();
		$query = "SELECT '.$column.' FROM #__PUNTOs_categorie WHERE id = " . $id;
		$db->setQuery($query);
		$image = $db->loadResult();
		$imagePath = JPATH_ROOT . '/media/com_puntos/images/categories/' . $image;
		unlink($imagePath);

		$query = 'UPDATE #__puntos_categorie SET ' . $column . ' = "" WHERE id = ' . $db->Quote($id);
		$db->setQuery($query);
		$db->query();
	}

	private function uploadFile($fileName)
	{
		$appl = JFactory::getApplication();
		$fileData = JRequest::getVar($fileName, 0, 'FILES');
		$msg = '';

		if ($fileData['name'] != '')
		{
			$user = JFactory::getUser();
			$name = time() . '_' . JString::substr($user->name, 0, 1) . '_' . $fileData['name'];
			$tmpName = $fileData['tmp_name'];

			$imageinfo = getimagesize($fileData['tmp_name']);
			$mime = $imageinfo['mime'];

			if ($mime != 'image/gif' && $mime != 'image/jpeg' && $mime != 'image/png')
			{
				$msg .= JText::_('COM_PUNTOS_IMAGE_MIME_NOT_SUPPORTED');
			}
			else
			{
				$blacklist = $this->blacklist;

				foreach ($blacklist as $item)
				{
					if (preg_match("/$item\$/i", $name))
					{
						$msg .= JText::sprintf('COM_PUNTOS_DONT_ALLOW_THIS_TYPE', $item);
					}
				}

				$uploadPath = JPATH_ROOT . '/media/com_puntos/images/categories/';
				$uploadImage = $uploadPath . basename($name);

				if (JFile::move($fileData['tmp_name'], $uploadImage))
				{
					$msg .= JText::sprintf('COM_PUNTOS_ICON_SUCCESSFULLY_UPLOADED_TO', $uploadImage);
				}
				else
				{
					$msg .= JText::sprintf('COM_PUNTOS_FAILED_TO_UPLOAD_ICON', $uploadPath);
				}

				$appl->enqueueMessage($msg);

				return basename($name);
			}
		}

		$appl->enqueueMessage($msg);

		return false;
	}

	
	public function cancel($key = null)
	{
		$link = 'index.php?option=com_puntos&view=categories';
		$this->setRedirect($link);
	}
}
