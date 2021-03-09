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
