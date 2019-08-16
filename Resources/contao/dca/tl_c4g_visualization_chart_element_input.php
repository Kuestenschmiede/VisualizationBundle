<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');
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
 * Table tl_c4g_visualization_chart_element_input
 */
$GLOBALS['TL_DCA']['tl_c4g_visualization_chart_element_input'] = array
(

	// Config
	'config' => array
	(
	    'label'                       => $GLOBALS['TL_CONFIG']['websiteTitle'],
	    'dataContainer'               => 'Table',
		'enableVersioning'            => true,  // I suppose it does not do anything, needs investigation
        'sql'                         => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )

	),

	// Fields
	'fields' => array
	(
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'elementId' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL"
        ),
        'x' => array
        (
            'sql'                     => "DECIMAL(20,10) unsigned NOT NULL default 0.0"
        ),
        'y' => array
        (
            'sql'                     => "DECIMAL(20,10) unsigned NOT NULL default 0.0"
        ),
    )
);

/**
 * Class tl_c4g_visualization_chart_element_input
 */
class tl_c4g_visualization_chart_element_input extends \Backend
{

}
