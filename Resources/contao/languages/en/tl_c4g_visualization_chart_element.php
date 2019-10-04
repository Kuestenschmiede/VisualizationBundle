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

$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element'] = [
    'new'               => ['Add new chart element', 'Add new chart element'],
    'edit'              => ['Edit','Edit chart element'],
    'copy'              => ['Copy','Copy chart element'],
    'delete'            => ['Delete','Delete chart element'],
    'show'              => ['Show','Show chart element'],
    'toggle'            => ['Publish/unpublish','Publish/unpublish chart element'],
    'all'               => ['Edit multiple','Edit multiple chart elements'],
    'id'                => ['ID', ''],
    'backendtitle'      => ['Title (Backend only)', 'Used for unique identification in the backend.'],
    'frontendtitle'     => ['Title (Frontend)', 'Display in the legend.'],
    'chartTitles'       => ['Charts', ''],
    'published'         => ['Publish chart element', 'Show the chart element on the website.'],
    'origin'            => ['Origin', 'How the data for this element should be loaded.'],
    'color'             => ['Color', 'The color in which this element is rendered.'],
    'type'              => ['Element type', 'The way the data is visualized. Keep in mind that some types are incompatible with each other.'],
    'inputWizard'       => ['Input', 'X and y value pairs to be loaded. Any number of pairs is allowed. If the data is expanded regularly, it should be saved in a database table.'],
    'xinput'            => ['X value', 'Up to ten places before and after the decimal point. In the case of a pie chart, the x values are ignored.'],
    'yinput'            => ['Y value', 'Up to ten places before and after the decimal point. In the case of a pie chart, the size of the pie slice is determined by the sum of the y values.'],
    'table'             => ['Table', 'The table from which the data is loaded.'],
    'tablex'            => ['Column for x values', 'The column whose values are interpreted as x values.'],
    'tabley'            => ['Column for y values', 'The column whose values are interpreted as y values.'],
    'whereWizard'       => ['Conditions', 'Conditions for loading data. Any number of conditions is allowed.'],
    'whereColumn'       => ['Column', 'The column whose value is checked.'],
    'whereComparison'   => ['Comparison', 'The comparison to be made.'],
    'whereValue'        => ['Value', 'The value checked against.'],
    'groupIdenticalX'   => ['Group identical x values', 'If multiple y values exist for the same x value, the values are added and the sum is shown instead of the individual values.'],
    'general_legend'    => 'Generic Options',
    'type_origin_legend'     => 'Type & Origin',
    'transform_legend'    => 'Data transformation',
    'publish_legend'    => 'Publish',
    'option_equal'      => 'equal to',
    'option_greater_or_equal' => 'greater or equal',
    'option_lesser_or_equal' => 'smaller or equal',
    'option_not_equal' => 'not equal',
    'option_greater' => 'greater than',
    'option_lesser' => 'smaller than',
    'option_input' => 'Input',
    'option_load_from_table' => 'Load from table',
    'option_line' => 'Line',
    'option_pie' => 'Pie',
    'option_bar' => 'Bar / Column',
];
