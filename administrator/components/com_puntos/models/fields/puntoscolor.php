<?php


defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('color');



class JFormFieldhotspotscolor extends JFormFieldColor
{
	
	public $type = 'categorymarkericon';


	
	protected function getInput()
	{
		
		if ($this->value)
		{
			$this->value = hotspotsHelperColor::rgb2hex($this->value);
		}

		return parent::getInput();
	}
}
