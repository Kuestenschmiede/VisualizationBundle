<?php


namespace con4gis\VisualizationBundle\Resources\contao\models;

use Contao\Model;

class ChartInputModel extends Model
{
    protected static $strTable = 'tl_c4g_visualization_chart_input';

    public static function findByChartId($id) {
        // Test
        return static::findBy('chartId', $id);
    }
}