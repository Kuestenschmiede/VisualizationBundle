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

use con4gis\VisualizationBundle\Classes\Charts\ChartElement;

/**
 * Table tl_c4g_visualization_chart_element
 */
$GLOBALS['TL_DCA']['tl_c4g_visualization_chart_element'] = array
(

	// Config
	'config' => array
	(
	    'label'                       => $GLOBALS['TL_CONFIG']['websiteTitle'],
	    'dataContainer'               => 'Table',
		'enableVersioning'            => true,
//	    'onload_callback'			  => array(array('tl_c4g_visualization_chart_element', 'updateDCA')),
//	    'onsubmit_callback'           => array(array('tl_c4g_visualization_chart_element', 'onSubmit')),
//		'ondelete_callback'			  => array(array('tl_c4g_visualization_chart_element', 'onDeleteForum')),
        'sql'                         => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )

	),

	// List
	'list' => array
	(
		'sorting' => array
		(
            'mode'                    => 2,
            'panelLayout'             => 'filter;sort,search,limit',
            'headerFields'            => array('backendtitle', 'frontendtitle'),
		),
		'label' => array
		(
			'fields'                  => array('backendtitle', 'frontendtitle'),
            'showColumns'             => true,
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			),
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
//				'button_callback'     => array('tl_c4g_visualization_chart_element', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(

        '__selector__'                => ['origin'],
	    'default'                     => '{general_legend},backendtitle,frontendtitle,color;'.
                                         '{type_origin_legend},type,origin;'.
                                         '{publish_legend},published;'
	),

    'subpalettes' => [
        'origin_1' => 'inputWizard',
        'origin_2' => 'table,tablex,tabley'
    ],

	// Fields
	'fields' => array
	(
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'published' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['published'],
            'default'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('class' => 'clr'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'frontendtitle' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['frontendtitle'],
            'inputType'               => 'text',
            'search'                  => 'true',
            'sorting'                 => 'true',
            'default'                 => '',
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255 ),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'backendtitle' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['backendtitle'],
            'inputType'               => 'text',
            'search'                  => 'true',
            'sorting'                 => 'true',
            'default'                 => '',
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255 ),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'color' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['color'],
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>6, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        'type' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['type'],
            'inputType'               => 'select',
            'options_callback'        => array('tl_c4g_visualization_chart_element', 'loadTypeOptions'),
            'eval'                    => array('submitOnChange' => true),
            'sql'                     => "varchar(10) NOT NULL default ''"
        ),
        'origin' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['origin'],
            'inputType'               => 'select',
            'default'                 => '1',
            'options_callback'        => array('tl_c4g_visualization_chart_element', 'loadOriginOptions'),
            'eval'                    => array('submitOnChange' => true),
            'sql'                     => "varchar(10) NOT NULL default ''"
        ),
        'inputWizard' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['inputWizard'],
            'inputType'               => 'multiColumnWizard',
            'save_callback'           => array(array('tl_c4g_visualization_chart_element', 'saveInput')),
            'load_callback'           => array(array('tl_c4g_visualization_chart_element', 'loadInput')),
            'eval'                    => array
            (
                'columnFields' => array
                (
                    'xinput' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['xinput'],
                        'inputType'               => 'text',
                        'eval'                    => array('maxlength'=>21, 'rgxp'=>'digit')
                    ),
                    'yinput' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['yinput'],
                        'inputType'               => 'text',
                        'eval'                    => array('maxlength'=>21, 'rgxp'=>'digit')
                    ),
                ),
                'doNotSaveEmpty'    => true,
            ),
        ),
        'table' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['table'],
            'inputType'               => 'text',
//            'options_callback'        => array('tl_c4g_visualization_chart_element', 'loadTableNames'),
            'eval'                    => array('maxlength'=>255),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'tablex' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['tablex'],
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'tabley' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['tabley'],
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        )
    )
);

/**
 * Class tl_c4g_visualization_chart_element
 */
class tl_c4g_visualization_chart_element extends \Backend
{
    public function loadOriginOptions(DataContainer $dc)
    {
        return array(
            '1' => 'Eingabe',
            '2' => 'aus Tabelle laden'
        );
    }

    public function loadTypeOptions(DataContainer $dc)
    {
        return array(
            ChartElement::TYPE_LINE => 'Linie',
            ChartElement::TYPE_PIE => 'Kuchenstück',
            ChartElement::TYPE_BAR => 'Vertikale Balken'
        );
    }

    /**
     * @param $value
     * @param DataContainer $dc
     * @return array
     */
    public function loadInput($value, DataContainer $dc) : array {
        $database = \Contao\Database::getInstance();
        $stmt = $database->prepare(
            "SELECT x as xinput, y as yinput FROM tl_c4g_visualization_chart_element_input WHERE elementId = ?");
        $result = $stmt->execute($dc->activeRecord->id);
        return $result->fetchAllAssoc();
    }

    /**
     * @param $value
     * @param DataContainer $dc
     * @return null
     */
    public function saveInput($value, DataContainer $dc) {
        $inputs = unserialize($value);
        if (is_array($inputs) === true) {
            $database = \Contao\Database::getInstance();
            $database->prepare(
                "DELETE FROM tl_c4g_visualization_chart_element_input WHERE elementId = ?")->execute($dc->activeRecord->id);
            foreach($inputs as $input) {
                $x = floatval($input['xinput']);
                $y = floatval($input['yinput']);
                if ($x !== 0.0 && $y !== 0.0) {
                    $stmt = $database->prepare(
                        "INSERT INTO tl_c4g_visualization_chart_element_input (elementId, x, y) ".
                        "VALUES (?, ?, ?)");
                    $stmt->execute($dc->activeRecord->id, $x, $y);
                }
            }
        }
        return null;
    }
    

}
