<?php
/**
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    7
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
 */
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

        foreach ($xValues as $key => $value) {
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
