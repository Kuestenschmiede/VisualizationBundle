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

use con4gis\CoreBundle\Classes\DCA\DCA;
use con4gis\CoreBundle\Classes\DCA\Fields\IdField;
use con4gis\CoreBundle\Classes\DCA\Fields\SQLField;

$dca = new DCA('tl_c4g_visualization_chart_element_period');
new IdField('id', $dca);
new SQLField('elementId', $dca, "int(10) unsigned NOT NULL");
new SQLField('fromX', $dca, "double NOT NULL default 0");
new SQLField('toX', $dca, "double NOT NULL default 0");
new SQLField('yinput', $dca, "varchar(255) NOT NULL default''");
$importId = new SQLField("importId", $dca, "int(20) unsigned NOT NULL default '0'");
$importId->eval()->doNotCopy(true);

/**
 * Class tl_c4g_visualization_chart_range
 */
class tl_c4g_visualization_chart_element_period extends \Contao\Backend
{

}
