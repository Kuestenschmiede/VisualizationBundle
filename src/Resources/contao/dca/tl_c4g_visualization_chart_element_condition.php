<?php

/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2021, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

use con4gis\CoreBundle\Classes\DCA\DCA;
use con4gis\CoreBundle\Classes\DCA\Fields\IdField;
use con4gis\CoreBundle\Classes\DCA\Fields\SQLField;

/**
 * Table tl_c4g_visualization_chart_element_condition
 */

$dca = new DCA('tl_c4g_visualization_chart_element_condition');
new IdField('id', $dca);
new SQLField('elementId', $dca, "int(10) unsigned NOT NULL");
new SQLField('whereColumn', $dca, "varchar(255) NOT NULL default ''");
new SQLField('whereComparison', $dca, "char(2) NOT NULL default''");
new SQLField('whereValue', $dca, "varchar(255) NOT NULL default ''");
new SQLField('and', $dca, "char(1) NOT NULL default '1'");
$importId = new SQLField("importId", $dca, "int(20) unsigned NOT NULL default '0'");
$importId->eval()->doNotCopy(true);

/**
 * Class tl_c4g_visualization_chart_element_condition
 */
class tl_c4g_visualization_chart_element_condition extends \Backend
{

}
