<?php

namespace con4gis\VisualizationBundle\Classes\Charts;

class Tooltip
{
    protected $title = [];

    public function createEncodableArray()
    {
        $array = [];
        if (!empty($this->title) === true) {
            $array['format']['title'] = $this->title;
        }

        return $array;
    }

    public function setTitle(int $value, string $formattedValue)
    {
        if (in_array($value, $this->title) === false) {
            $this->title[$value] = $formattedValue;
        }
    }
}
