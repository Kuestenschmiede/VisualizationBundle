<?php
/*
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    6
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
 */
/**
 * Global settings
 */
$GLOBALS['con4gis']['visualization']['installed'] = true;

/**
 * Backend modules
 */
array_insert($GLOBALS['BE_MOD'], array_search('con4gis_core', array_keys($GLOBALS['BE_MOD'])) + 2,
    ['con4gis_visualization' => [
        'tl_c4g_visualization_chart' => array
        (
            'tables' 		=> array('tl_c4g_visualization_chart')
        ),
        'tl_c4g_visualization_chart_element' => array
        (
            'tables' 		=> array('tl_c4g_visualization_chart_element')
        ),

    ]]
);

/**
 * MODELS
 */
$GLOBALS['TL_MODELS']['tl_c4g_visualization_chart_element_input'] = \con4gis\VisualizationBundle\Resources\contao\models\ChartElementModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_visualization_chart_element'] = \con4gis\VisualizationBundle\Resources\contao\models\ChartElementModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_visualization_chart_element_relation'] = \con4gis\VisualizationBundle\Resources\contao\models\ChartElementRelationModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_visualization_chart'] = \con4gis\VisualizationBundle\Resources\contao\models\ChartModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_visualization_chart_range'] = \con4gis\VisualizationBundle\Resources\contao\models\ChartRangeModel::class;

/**
 * Content elements
 */
$GLOBALS['TL_CTE']['con4gis']['c4g_visualization'] = \con4gis\VisualizationBundle\Resources\contao\elements\ChartContentElement::class;