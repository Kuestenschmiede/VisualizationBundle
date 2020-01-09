<?php

namespace con4gis\VisualizationBundle\Classes\Transformers;

class Multiplier implements Transformer
{
    protected $factor;
    protected $axis;

    public function __construct(float $factor, bool $yValues = true)
    {
        $this->factor = $factor;
        if ($yValues === true) {
            $this->axis = 'y';
        } else {
            $this->axis = 'x';
        }
    }

    public function transform(array $dataPoints): array
    {
        foreach ($dataPoints as $key => $value) {
            $dataPoints[$key][$this->axis] = $value[$this->axis] * $this->factor;
        }

        return $dataPoints;
    }
}
