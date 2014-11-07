<?php

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldMarkerImage extends JFormField
{

	public $type = 'Markerimage';


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
		$script = JURI::root() . '/media/com_puntos/js/lazy/LazyLoad.js';
		$categories = JURI::root() . '/media/com_puntos/js/fields/markerImage.js';
		$document->addScript($script);
		$document->addScript($categories);

		$domready = 'window.addEvent("domready", function() {
            var options = {fieldId:"' . $this->id . '"}
            new markerImage(options);

        })';

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
			JHTML::_('select.option', '0', JText::_('COM_PUNTOS_SAME_MARKER_AS_CATEGORY')),
			JHTML::_('select.option', '1', JText::_('COM_PUNTOS_CUSTOM_MARKER'))
		);

		$html[] = '<div id="current-icon">';

		if ($image)
		{
			$html[] = '<img src="' . $image . '" />';
		}

		$html[] = '</div>';

		$html[] = JHTML::_('select.genericlist', $options, 'marker-image', null, 'value', 'text', $selected);

		$html[] = '<div class="clr"></div>';
		$html[] = '<div id="sample-image">';

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
