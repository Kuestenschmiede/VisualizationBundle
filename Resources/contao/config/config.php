<?php
/*
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
/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['con4gis'] = array_merge($GLOBALS['BE_MOD']['con4gis'], [
        'c4g_visualization_chart' => array
        (
            'tables' 		=> array('tl_c4g_visualization_chart'),
            'stylesheet' => 'bundles/con4gisvisualization/css/backend_chart.css'
        ),
        'c4g_visualization_chart_element' => array
        (
            'tables' 		=> array('tl_c4g_visualization_chart_element'),
            'stylesheet' => 'bundles/con4gisvisualization/css/backend_chart_element.css'
        ),

    ]
);

if(TL_MODE == "BE") {
    $GLOBALS['TL_CSS'][] = '/bundles/con4gisvisualization/css/con4gis.css';
}

/**
 * MODELS
 */
$GLOBALS['TL_MODELS']['tl_c4g_visualization_chart_element_condition'] = \con4gis\VisualizationBundle\Resources\contao\models\ChartElementConditionModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_visualization_chart_element_input'] = \con4gis\VisualizationBundle\Resources\contao\models\ChartElementModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_visualization_chart_element'] = \con4gis\VisualizationBundle\Resources\contao\models\ChartElementModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_visualization_chart_element_relation'] = \con4gis\VisualizationBundle\Resources\contao\models\ChartElementRelationModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_visualization_chart'] = \con4gis\VisualizationBundle\Resources\contao\models\ChartModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_visualization_chart_range'] = \con4gis\VisualizationBundle\Resources\contao\models\ChartRangeModel::class;

/**
 * Content elements
 */
$GLOBALS['TL_CTE']['con4gis']['c4g_visualization'] = \con4gis\VisualizationBundle\Resources\contao\elements\ChartContentElement::class;