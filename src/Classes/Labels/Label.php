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
namespace con4gis\VisualizationBundle\Classes\Labels;

abstract class Label
{
    const Y = '{y}';
    const X = '{x}';

    const OUTSIDE = 'outside';

    protected $label;
    protected $color;
    protected $placement;

    abstract public function label(array $dataPoints): array;
}
