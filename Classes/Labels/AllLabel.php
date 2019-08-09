<?php


namespace con4gis\VisualizationBundle\Classes\Labels;

class AllLabel extends Label
{
    public function __construct(string $label)
    {
        $this->label = $label;
    }

    public function label(array $dataPoints): array
    {
        foreach ($dataPoints as $key => $value) {
            $dataPoints[$key]['indexLabel'] = $this->label;
        }
        return $dataPoints;
    }
}