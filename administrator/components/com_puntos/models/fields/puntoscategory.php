<?php


defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');


class JFormFieldPuntosCategory extends JFormFieldList
{
	
	public $type = 'PuntosCategory';

	
	protected function getOptions()
	{

		$appl = JFactory::getApplication();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.id AS value, a.cat_name AS text, a.published, a.params');
		$query->from('#__puntos_categorie AS a');
		$query->where('a.published = 1');

		
		$query->group('a.id, a.cat_name, a.published');
		$query->order('a.cat_name ASC');

		
		$db->setQuery($query);

		$options = $db->loadObjectList();

		if ($appl->isSite())
		{
			foreach ($options as $key => $value)
			{
				$registry = new JRegistry($value->params);

				if ($registry->get('exclude_frontend', 0))
				{
					unset($options[$key]);
				}
			}
		}

		if (isset($this->element['none']))
		{
			array_unshift($options, JHtml::_('select.option', '-1', JText::_('COM_PUNTOS_CATEGORY_NONE')));
		}

		
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
