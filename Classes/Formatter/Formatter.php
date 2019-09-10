<?php


use con4gis\VisualizationBundle\Classes\Source\Source;

interface Formatter
{
    public function setSource(Source $source);

    public function getFormat();
}