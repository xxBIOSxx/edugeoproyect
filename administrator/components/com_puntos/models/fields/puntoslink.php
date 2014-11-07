<?php


defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');


class JFormFieldPuntosLink extends JFormField
{
    
    public $type = 'Puntoslink';

    public function __construct($form = null)
    {
        parent::__construct($form);
    }

    protected function getInput()
    {

        $plugins = JPluginHelper::getPlugin('puntoslinks');
        JPluginHelper::importPlugin('puntoslinks');

        $options[] = JHtml::_('select.option', 0, JText::_('COM_PUNTOS'));
        foreach ($plugins as $value) {
            $options[] = JHtml::_('select.option', $value->name, JText::_('PLG_PUNTOSLINKS_COM_'.strtoupper($value->name)));
        }

        $document = JFactory::getDocument();
        $js = JURI::root() . '/media/com_puntos/js/fields/puntosLink.js';
        $document->addScript($js);
        $domready = 'window.addEvent("domready", function() {
            var options = {fieldId:"' . $this->id . '",link_to_id:"'.$this->id.'_id'.'"};
            new puntosLink(options);

        })';
        $document->addScriptDeclaration($domready);

        $html = array();
        $selected = 0;
        if ($this->value) {
            $selected = $this->value;
        }

        $html[] = JHTML::_('select.genericlist', $options, 'jform[params][link_to]' , null, 'value', 'text', $selected, $this->id);

        $link_id = isset($this->form->getValue('params')->link_to_id) ?
                                    $this->form->getValue('params')->link_to_id : '';

        $html[] = '<div class="clr"></div>';

        $display = ($selected !== 0) ? 'block' : 'none';
        $html[] = '<div id="link_to_plugins" style="display:'.$display.'">';
        $html[] = '<input type="text" id="' . $this->id .'_id'
                            . '" name="jform[params][link_to_id]" value="' . $link_id . '" />';
        $html[] = '</div>';

        return implode("\n", $html);
    }
}
