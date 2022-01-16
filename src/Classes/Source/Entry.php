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
namespace con4gis\VisualizationBundle\Classes\Source;

class Entry
{
    protected $values;

    public function __construct(array $array, $min = 0, $redirectSite = '')
    {
        $array['min'] = $min;
        $array['redirectSite'] = $redirectSite;

        $this->values = $array;
    }

    public function get($index)
    {
        return $this->values[$index];
    }
}
