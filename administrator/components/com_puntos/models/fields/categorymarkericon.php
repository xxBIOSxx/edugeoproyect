<?php


defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('list');



class JFormFieldCategorymarkericon extends JFormField
{
	
	public $type = 'categorymarkericon';

	
	public function __construct($form = null)
	{
		parent::__construct($form);

	
		$document = JFactory::getDocument();
		$css = JURI::root() . '/media/com_puntos/css/fields/markerImage.css';
		$document->addStyleSheet($css);
	}


	protected function getInput()
	{
		jimport('joomla.filesystem.folder');

	
		$document = JFactory::getDocument();
		JHTML::_('script', 'media/com_puntos/js/fixes.js');

		JHTML::_('script', 'media/com_puntos/js/lazy/LazyLoad.js', '');
		JHTML::_('script', 'media/com_puntos/js/modules/backend/category.js');

		$domready = "window.addEvent('domready', function() {
			var puntoCategory = new compojoom.puntos.modules.categories();
		});";

		$document->addScriptDeclaration($domready);

		
		$html = array();
		$path = JPATH_ROOT . '/media/com_puntos/images/categories/sample';
		$exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'index.html', 'blank.gif');
		$sampleIcons = JFolder::files($path, '.', false, false, $exclude);

		$selected = 0;
		$image = '';

		if ($this->value)
		{
			$selected = 1;
			$image = JURI::root() . 'media/com_puntos/images/categories/' . $this->value;
		}

		$options = array(
			JHTML::_('select.option', '', JText::_('COM_PUNTOS_SELECT')),
			JHTML::_('select.option', 'new', JText::_('COM_PUNTOS_UPLOAD_NEW_IMAGE')),
			JHTML::_('select.option', 'delete', JText::_('COM_PUNTOS_DELETE_CURRENT_IMAGE')),
			JHTML::_('select.option', 'sample', JText::_('COM_PUNTOS_SELECT_SAMPLE_IMAGE'))
		);

		$html[] = '<div id="category-icon">';

		if ($image)
		{
			$html[] = '<img src="' . $image . '" />';
		}

		$html[] = '</div>';

		$html[] = JHTML::_('select.genericlist', $options, 'select-icon', null, 'value', 'text', $selected);

		$html[] = '<div class="clear-both"></div>';
		$html[] = '<input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>';
		$html[] = '<div id="iconupload" style="display: none;">';
		$html[] = '<input type="file" name="cat_icon" id="cat_icon"/>';
		$html[] = '</div>';
		$html[] = '<div id="deleteicon-text" style="display: none;">';
		$html[] = JText::_('COM_PUNTOS_OLD_ICON_WILL_BE_DELETED_WHEN_SAVING');
		$html[] = '</div>';

		$html[] = '<div class="clr"></div>';
		$html[] = '<div id="select-sample-image" style="display: none">';

		foreach ($sampleIcons as $icon)
		{
			$title = explode('.', $icon);

			$path = JURI::root() . 'media/com_puntos/images/categories/sample/' . $icon;
			$html[] = '<div>';
			$html[] = '<img src="' . JURI::root() . 'media/com_puntos/images/categories/sample/blank.gif"
             data-src="' . $path . '" title="' . $title[0] . '"/>';
			$html[] = '<span data-id="' . $icon . '"> ' . $title[0] . '</span>';
			$html[] = '</div>';
		}

		$html[] = '</div>';

		$html[] = '<input type="hidden" id="' . $this->id . '" name="' . $this->name . '" value="' . $this->value . '" />';

		return implode("\n", $html);
	}
}
