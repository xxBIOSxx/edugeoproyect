<?php


defined('_JEXEC') or die('Restricted access');


class PuntosMapPoint
{
    public $x, $y;

 
    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

  
    public function __toString()
    {
        return "({$this->x},{$this->y})";
    }
}