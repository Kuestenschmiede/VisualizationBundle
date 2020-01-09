<?php

namespace con4gis\VisualizationBundle\Classes\Labels;

abstract class Label
{
    const Y = '{y}';
    const X = '{x}';

    const OUTSIDE = 'outside';

    protected $label;
    protected $color;
    protected $placement;

    abstract public function label(array $dataPoints): array;
}
