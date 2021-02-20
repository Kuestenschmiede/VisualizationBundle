<?php

namespace con4gis\VisualizationBundle\Classes\Source;

class Entry
{
    protected $values;

    public function __construct(array $array, $min=0, $redirectSite='')
    {
        $array['min'] = $min;
        $array['redirectSite'] = $redirectSite;

        $this->values = $array;
    }

    public function get($index)
    {
        return $this->values[$index];
    }
}
