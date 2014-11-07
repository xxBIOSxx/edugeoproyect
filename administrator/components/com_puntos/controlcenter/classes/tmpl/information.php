<?php

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
JHTML::_('stylesheet', 'ccc.css', 'media/compojoomcc/css/');
JHTML::_('script', 'ccc.js', 'media/compojoomcc/js/');


$modules = JModuleHelper::getModules('ccc_'. $this->config->extensionPosition . '_information');

?>

<div id="ccc_information">
    <div id="ccc_information_inner">
        <h2><?php echo JText::_('COMPOJOOM_CONTROLCENTER_VERSION'); ?></h2>
        <p>
            <?php echo $this->config->version; ?>
        </p>
        <h2><?php echo JText::_('COMPOJOOM_CONTROLCENTER_COPYRIGHT'); ?></h2>
        <p>
            <?php echo $this->config->copyright; ?>
        </p>
        <h2><?php echo JText::_('COMPOJOOM_CONTROLCENTER_LICENSE'); ?></h2>
        <p>
            <?php echo $this->config->license; ?>
        </p>
        <h2><?php echo JText::_('COMPOJOOM_CONTROLCENTER_TRANLATION'); ?></h2>
        <p>
            <?php echo $this->config->translation; ?>
        </p>
      
        <p>
            <?php echo JText::_($this->config->description); ?>
        </p>
        <h2>Gracias Totales!!!</h2>
        <p>
            Este software no habría sido posible sin la ayuda de los mencionados aquí. 
             GRACIAS por su ayuda continua, apoyo e inspiración!
        </p>
        <ul>
            <?php echo JText::_($this->config->thankyou); ?>
        </ul>

    </div>
    <div id="ccc_information_modules">
        <?php
        foreach ($modules as $modules) {
            $output = JModuleHelper::renderModule($module);
            echo $output;
        }
        ?>
    </div>
</div>