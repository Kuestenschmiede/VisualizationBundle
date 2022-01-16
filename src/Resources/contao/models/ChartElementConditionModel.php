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

namespace con4gis\VisualizationBundle\Resources\contao\models;

use Contao\Model;

class ChartElementConditionModel extends Model
{
    protected static $strTable = 'tl_c4g_visualization_chart_element_condition';

    public static function findByElementId($elementId) {
        return static::findBy('elementId', $elementId);
    }
}