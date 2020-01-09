<?php

namespace con4gis\VisualizationBundle\Classes\Source;

class Entry
{
    protected $values;

    public function __construct(array $array)
    {
        $this->values = $array;
    }

    public function get($index)
    {
        return $this->values[$index];
    }
}
