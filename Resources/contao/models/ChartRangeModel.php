<?php


namespace con4gis\VisualizationBundle\Resources\contao\models;

use Contao\Model;

class ChartRangeModel extends Model
{
    protected static $strTable = 'tl_c4g_visualization_chart_range';

    public static function findByChartId($chartId) {
        return static::findBy('chartId', $chartId);
    }
}