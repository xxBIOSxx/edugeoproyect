<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
jimport('joomla.filesystem');


class PuntosViewCategory extends PuntosView
{

	public function display($tpl = null)
	{
		$uri = JFactory::getURI();
		$this->form = $this->get('Form');
		$this->row = $this->get('Item');
		jimport('joomla.filesystem.folder');
		$lists = array();
		$options[] = JHtml::_('select.option', 0, JText::_('JNO'));
		$options[] = JHtml::_('select.option', 1, JText::_('JYES'));
		$lists['published'] = JHtml::_('select.genericlist', $options, 'published', 'class="inputbox"', 'value', 'text', $this->row->published);

		$path = JPATH_ROOT . '/media/com_puntos/images/categories/sample';
		$exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'index.html', 'blank.gif');
		$sampleIcons = JFolder::files($path, '.', false, false, $exclude);

		$iconPack = array();

		foreach ($sampleIcons as $key => $icon)
		{
			$title = explode('.', $icon);
			$iconPack[$key]['title'] = $title[0];
			$iconPack[$key]['path'] = JURI::root() . 'media/com_puntos/images/categories/sample/' . $icon;
			$iconPack[$key]['original'] = $icon;
		}

		$path = JPATH_ROOT . '/media/com_puntos/images/categories/sample_shadow';
		$exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'index.html', 'blank.gif');
		$sampleShadows = JFolder::files($path, '.', false, false, $exclude);

		$shadowPack = array();

		foreach ($sampleShadows as $key => $icon)
		{
			$title = explode('.', $icon);
			$shadowPack[$key]['title'] = $title[0];
			$shadowPack[$key]['path'] = JURI::root() . 'media/com_puntos/images/categories/sample_shadow/' . $icon;
			$shadowPack[$key]['original'] = $icon;
		}

		$this->sampleIcons = $iconPack;
		$this->sampleShadows = $shadowPack;
		$this->lists = $lists;
		$this->request_url = $uri->toString();
		$this->user = JFactory::getUser();

		$this->addToolbar();
		parent::display($tpl);
	}

	public function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_PUNTOS_EDIT_CATEGORY'), 'categories');
		JToolBarHelper::save('category.save');
		JToolBarHelper::apply('category.apply');
		JToolBarHelper::cancel('category.cancel');
	}
}
