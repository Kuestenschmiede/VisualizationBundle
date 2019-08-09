<?php


namespace con4gis\VisualizationBundle\Resources\contao\models;

use Contao\Model;

class ChartElementInputModel extends Model
{
    protected static $strTable = 'tl_c4g_visualization_chart_element_input';

    public static function findByElementId($elementId) {
        return static::findBy('elementId', $elementId);
    }
}