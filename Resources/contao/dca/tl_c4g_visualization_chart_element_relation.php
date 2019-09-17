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

use con4gis\CoreBundle\Classes\DCA\DCA;
use con4gis\CoreBundle\Classes\DCA\Fields\IdField;
use con4gis\CoreBundle\Classes\DCA\Fields\SQLField;

$dca = new DCA('tl_c4g_visualization_chart_element_relation');
new IdField('id', $dca);
new SQLField('chartId', $dca, "int(10) unsigned NOT NULL");
new SQLField('elementId', $dca, "int(10) unsigned NOT NULL");


/**
 * Class tl_c4g_visualization_chart_element_relation
 */
class tl_c4g_visualization_chart_element_relation extends \Backend
{

}
