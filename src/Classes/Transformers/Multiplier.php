<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2022, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */
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
