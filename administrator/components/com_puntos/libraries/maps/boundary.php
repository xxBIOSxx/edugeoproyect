<?php


defined('_JEXEC') or die('Restricted access');


class PuntosMapBoundary
{
    public $x, $y, $width, $height;


    public function __construct($x, $y, $width, $height)
    {
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
    }

  
    public function __toString()
    {
        return "({$this->x},{$this->y},{$this->width},{$this->height})";
    }
}