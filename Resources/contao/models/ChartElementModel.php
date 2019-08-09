<?php


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
            $ids[] = $relation->id;
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