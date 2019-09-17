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

$GLOBALS['TL_LANG']['tl_c4g_visualization_chart'] =
[
    'new'               => ['Add new chart', 'Add new chart'],
    'edit'              => ['Edit','Edit chart'],
    'copy'              => ['Copy','Copy chart'],
    'delete'            => ['Delete','Delete chart'],
    'show'              => ['Show','Show chart'],
    'toggle'            => ['Publish/unpublish','Publish/unpublish chart'],
    'all'               => ['Edit multiple','Edit multiple charts'],
    'id'                => ['ID', ''],
    'backendtitle'      => ['Title (Backend only)', 'Used for unique identification in the backend.'],
    'published'         => ['Publish chart', 'Show the chart on the website.'],
    'zoom'              => ['Activate zoom', 'If checked, the user can zoom into the chart.'],
    'swapAxes'          => ['Swap axes', 'If checked, the x and y axes swap places.'],
    'xValueCharacter'   => ['X value character', 'Defines the character of the values on the x axis. This influences the form and the way the data is loaded.'],
    'xTimeFormat'       => ['Time format', 'Format string for the timestamp.'],
    'xLabelCount'       => ['Show every ...th label', 'The number of labels should be displayed below the X-axis. Default: 1'],
    'xshow'             => ['Show x axis', 'Whether to show the x axis or not.'],
    'xLabelText'        => ['X axis label', 'Defines the label for the x axis.'],
    'xLabelPosition'    => ['Label position', 'Defines the position of the label relatively to the axis.'],
    'yLabelText'        => ['Y axis label', 'Defines the label for the y axis.'],
    'yLabelPosition'    => ['Label position', 'Defines the position of the label relatively to the axis.'],
    'yshow'             => ['Show y axis', 'Whether to show the y axis or not.'],
    'yInverted'         => ['Invert y axis', 'If checked, the y axis begins at the top instead of the bottom.'],
    'y2LabelText'       => ['Secondary Y axis label', 'Defines the label for the secondary y axis.'],
    'y2LabelPosition'   => ['Label position', 'Defines the position of the label relatively to the axis.'],
    'y2show'            => ['Show secondary y axis', 'Whether to show the secondary y axis or not.'],
    'y2Inverted'        => ['Invert secondary y axis', 'If checked, the secondary y axis begins at the top instead of the bottom.'],
    'elementWizard'     => ['Elements', 'The chart elements to be shown in this chart.'],
    'elementId'         => ['Element'],
    'image'             => ['Image file', 'The image file the watermark is based on.'],
    'imageMaxHeight'    => ['Maximum image height (pixels)', 'The aspect ratio is retained.'],
    'imageMaxWidth'     => ['Maximum image width (pixels)', 'The aspect ratio is retained.'],
    'imageMarginTop'    => ['Upper margin (pixels)', 'Upper margin relatively to the chart\'s upper bound.'],
    'imageMarginLeft'   => ['Left margin (pixels)', 'Left margin relatively to the chart\'s left bound.'],
    'imageOpacity'      => ['Image opacity (percent)', 'Image opacity in percent.'],
    'rangeWizardNominal'=> ['Defines ranges', 'Each range is shown separately. You can switch between the ranges using buttons.'],
    'rangeWizardTime'   => ['Defines ranges', 'Each range is shown separately. You can switch between the ranges using buttons.'],
    'name'              => ['Caption', 'The button caption.'],
    'fromX'             => ['From', 'From this x value onward, the values are shown (inclusive).'],
    'toX'               => ['To', 'Up to this x value, the values are shown (inclusive).'],
    'defaultRange'      => ['Default', 'Default range. If more than one are checked, the first is considered the default.'],
    'buttonAllCaption'  => ['"All"-Button', 'If this field is not empty, a button to display all values is rendered. The inputted text is the button caption.'],
    'buttonAllPosition' => ['"All"-Button position', 'Defines whether the button for all values is rendered as the first or last button.'],
    'buttonPosition'    => ['Button position', 'Defines the position of the buttons relatively to the chart.'],
    'loadOutOfRangeData'=> ['Load data out of range', 'When working with large data pool or if only the defined ranges should be shown, it may be sensible to limit loading the data.'],
    'general_legend'    => 'Generic options',
    'element_legend'    => 'Elements',
    'color_legend'      => 'Colors',
    'watermark_legend'  => 'Watermark',
    'ranges_legend'     => 'Ranges',
    'coordinate_system_legend'          => 'Coordinate system',
    'expert_legend'     => 'Expert options',
    'publish_legend'    => 'Publish',
    'option_as_first'   => 'as first',
    'option_as_last'    => 'as last',
    'option_top_left'   => 'Top left',
    'option_top_middle' => 'Top middle',
    'option_top_right'  => 'Top right',
    'option_bottom_left' => 'Bottom left',
    'option_bottom_middle' => 'Bottom middle',
    'option_bottom_right' => 'Bottom right',
    'option_inner_right_up' => 'Inner right/up',
    'option_inner_middle' => 'Inner middle',
    'option_inner_left_down' => 'Inner left/down',
    'option_outer_right_up' => 'outer right/up',
    'option_outer_middle' => 'outer middle',
    'option_outer_left_down' => 'outer left/down',
    'option_nominal_values' => 'Nominal values',
    'option_temporal_values' => 'Temporal values'
];
