<?php

defined('_JEXEC') or die();
jimport('joomla.application.component.modeladmin');

class PuntosModelKml extends JModelAdmin
{

	
	protected $text_prefix = 'COM_PUNTOS';


	public function save($data)
	{

		$file = JRequest::getVar('jform', '', 'files', 'array');
		$emptyFile = true;
		if (!empty($file)) {
			if (!empty($file['name']['kml_file'])) {
				foreach ($file as $key => $value) {
					$newFile[$key] = $value['kml_file'];
				}
				$emptyFile = false;
			}
		}


		$filedef = false;
		if (!$emptyFile) {
			$filedef = $this->uploadFile($newFile);
		}
		if ($filedef !== false) {
			$data['original_filename'] = $filedef['original_filename'];
			$data['mangled_filename'] = $filedef['mangled_filename'];
			$data['mime_type'] = $filedef['mime_type'];
		}

		return parent::save($data);
	}

	
	public function uploadFile($file)
	{

		if (isset($file['name'])) {

			$serverkey = JFactory::getConfig()->get('secret', '');
			$sig = $file['name'] . microtime() . $serverkey;
			if (function_exists('sha256')) {
				$mangledname = sha256($sig);
			} elseif (function_exists('sha1')) {
				$mangledname = sha1($sig);
			} else {
				$mangledname = md5($sig);
			}

			$filepath = JPath::clean(JPATH_ROOT . '/media/com_puntos/kmls/' . $mangledname);

			if (JFile::exists($filepath)) {
				$this->setError(JText::_('COM_PUNTOS_ATTACHMENTS_ERR_NAMECLASH'));
				return false;
			}

			jimport('joomla.filesystem.file');
			if (!JFile::upload($file['tmp_name'], $filepath)) {
				$this->setError(JText::_('COM_PUNTOS_ATTACHMENTS_ERR_CANTJFILEUPLOAD'));
				return false;
			}

			if (function_exists('mime_content_type')) {
				$mime = mime_content_type($filepath);
			} elseif (function_exists('finfo_open')) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $filepath);
			} else {
				$mime = 'application/octet-stream';
			}

			return array(
				'original_filename' => $file['name'],
				'mangled_filename' => $mangledname,
				'mime_type' => $mime
			);
		} else {
			$this->setError(JText::_('COM_PUNTOS_ATTACHMENTS_ERR_NOFILE'));
			return false;
		}
	}

	
	public function getTable($type = 'Kmls', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true)
	{

		$form = $this->loadForm('com_puntos.kmls', 'kml', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) {
			return false;
		}

		return $form;
	}

	
	protected function loadFormData()
	{

		$data = JFactory::getApplication()->getUserState('com_puntos.edit.kml.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

}