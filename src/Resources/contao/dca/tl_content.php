<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 10
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2025, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */


$GLOBALS['TL_DCA']['tl_content']['palettes']['c4g_visualization'] = '{type_legend},name,type,headline,invisible,cssID;{chart_legend},chartID';

$GLOBALS['TL_DCA']['tl_content']['fields']['chartID'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['c4g_visualization']['chartID'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_c4g_visualization_chart.backendtitle',
    'eval'                    => ['tl_class'=>'clr'],
    'sql'                     => "int(10) NOT NULL default 0"
];

