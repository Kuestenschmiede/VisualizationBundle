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

namespace con4gis\VisualizationBundle\Resources\contao\models;

use Contao\Database;
use Contao\Model;

class ChartElementModel extends Model
{
    protected static $strTable = 'tl_c4g_visualization_chart_element';

    public static function findByChartId($chartId) {
        $relations = ChartElementRelationModel::findByChartId($chartId);
        $ids = [];
        foreach ($relations as $relation) {
            $ids[] = $relation->elementId;
        }
        if (!empty($ids) === true) {
            return static::findMultipleByIds($ids);
        } else {
            return null;
        }
    }

    public static function findByElementId($elementId) {
        return static::findBy('elementId', $elementId);
    }
}