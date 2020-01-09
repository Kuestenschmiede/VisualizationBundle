<?php

namespace con4gis\VisualizationBundle\Classes\Transformers;

class GroupIdenticalXTransformer implements Transformer
{
    public function transform(array $dataPoints): array
    {
        $grouped = [];
        $xValues = [];
        $yValues = [];
        foreach ($dataPoints as $dataPoint) {
            $arrayKey = array_search($dataPoint['x'], $xValues);
            if ($arrayKey === false) {
                $xValues[] = $dataPoint['x'];
                $yValues[] = $dataPoint['y'];
            } else {
                $yValues[$arrayKey] = intval($yValues[$arrayKey]) + intval($dataPoint['y']);
            }
        }

        $i = 0;
        while ($i < count($xValues)) {
            $grouped[] = [
                'x' => $xValues[$i],
                'y' => $yValues[$i],
            ];
            $i += 1;
        }

        return $grouped;
    }
}
