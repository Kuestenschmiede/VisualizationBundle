<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 10
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2025, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */
namespace con4gis\VisualizationBundle\Classes\Transformers;

use Safe\DateTime;

class ExtractYearFromXTimestampTransformer implements Transformer
{
    public function transform(array $dataPoints): array
    {
        $transformed = [];
        foreach ($dataPoints as $dataPoint) {
            $time = new DateTime();
            $time->setTimestamp($dataPoint['x']);
            $time->setDate(1970, (int) $time->format('m'), (int) $time->format('d'));
            $time->setTime(0, 0);
            $dataPoint['x'] = $time->getTimestamp();
            $transformed[$time->getTimestamp()] = $dataPoint;
        }
        return array_values($transformed);
    }
}
