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
namespace con4gis\VisualizationBundle\Classes\Charts;

class Tooltip
{
    protected $title = [];

    public function createEncodableArray()
    {
        $array = [];
        if (!empty($this->title) === true) {
            $array['format']['title'] = $this->title;
        }

        return $array;
    }

    public function setTitle(int $value, string $formattedValue)
    {
        if (in_array($value, $this->title) === false) {
            $this->title[$value] = $formattedValue;
        }
    }
}
