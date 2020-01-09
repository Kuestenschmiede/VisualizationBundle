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

use con4gis\CoreBundle\Classes\DCA\DCA;
use con4gis\CoreBundle\Classes\DCA\Fields\IdField;
use con4gis\CoreBundle\Classes\DCA\Fields\SQLField;

$dca = new DCA('tl_c4g_visualization_chart_element_input');
new IdField('id', $dca);
new SQLField('elementId', $dca, "int(10) unsigned NOT NULL");
new SQLField('x', $dca, "DECIMAL(20,10) signed NOT NULL default 0.0");
new SQLField('y', $dca, "DECIMAL(20,10) signed NOT NULL default 0.0");

/**
 * Class tl_c4g_visualization_chart_element_input
 */
class tl_c4g_visualization_chart_element_input extends \Backend
{

}
