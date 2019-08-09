<?php

namespace con4gis\VisualizationBundle\Classes\Transformers;

class Interpolator implements Transformer
{
    protected $interval = 1;

    public function __construct(int $interval) {
        $this->interval = $interval;
    }

    public function transform(array $dataPoints): array
    {
        $interpolated = [];

        $interpolated[] = $dataPoints[0];

        $size = sizeof($dataPoints);
        $index = 1;
        while ($index <= ($size - 1)) {

            $x1 = $dataPoints[$index - 1]['x'];
            $x2 = $dataPoints[$index]['x'];
            $y1 = $dataPoints[$index - 1]['y'];
            $y2 = $dataPoints[$index]['y'];

            $xD = $x2 - $x1;
            $yD = $y2 - $y1;

            $z1D = $yD / $xD;

            $ziD = $z1D * $this->interval;

            if ($xD % $this->interval === 0) {
                $xi = $x1;
                $yi = $y1;
                while (($xi + $this->interval) < $x2) {
                    $xi += $this->interval;
                    $yi += $ziD;
                    $interpolated[] = [
                        'x' => $xi,
                        'y' => $yi,
                    ];
                }
            }

            $interpolated[] = $dataPoints[$index];

            $index += 1;
        }
        return $interpolated;
    }
}