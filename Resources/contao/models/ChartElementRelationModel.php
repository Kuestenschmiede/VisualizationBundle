<?php


namespace con4gis\VisualizationBundle\Resources\contao\models;

use Contao\Model;

class ChartElementRelationModel extends Model
{
    protected static $strTable = 'tl_c4g_visualization_chart_element_relation';

    public static function findByChartId($chartId) {
        return static::findBy('chartId', $chartId);
    }
    public static function findByElementId($elementId) {
        return static::findBy('elementId', $elementId);
    }
}