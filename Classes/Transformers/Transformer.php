<?php

namespace con4gis\VisualizationBundle\Classes\Transformers;

interface Transformer
{
    public function transform(array $dataPoints) : array;
}