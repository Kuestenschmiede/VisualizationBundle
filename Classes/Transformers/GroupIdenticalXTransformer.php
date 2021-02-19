<?php

namespace con4gis\VisualizationBundle\Classes\Transformers;

class GroupIdenticalXTransformer implements Transformer
{
    public function transform(array $dataPoints): array
    {
        $grouped = [];
        $xValues = [];
        $yValues = [];
        $minValues = [];
        foreach ($dataPoints as $dataPoint) {
            $arrayKey = array_search($dataPoint['x'], $xValues);
            if ($arrayKey === false) {
                $xValues[] = $dataPoint['x'];
                $yValues[] = intval($dataPoint['y']);
                $minValues[] = $dataPoint['min'];
            } else {
                $yValues[$arrayKey] = intval($yValues[$arrayKey]) + intval($dataPoint['y']);
            }
        }

        foreach ($xValues as $key=>$value) {
            if ($yValues[$key] && ($yValues[$key] >= $minValues[$key])) {
                $grouped[] = [
                    'x' => $xValues[$key],
                    'y' => $yValues[$key],
                ];
            }
        }

        return $grouped;
    }
}
