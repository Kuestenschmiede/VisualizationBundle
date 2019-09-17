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

$palettes = [
    'general' => '{general_legend},backendtitle,xValueCharacter,',
    'elements' => 'elementWizard;',
    'ranges_nominal' => '{ranges_legend},rangeWizardNominal,buttonAllCaption,buttonPosition,buttonAllPosition,loadOutOfRangeData;',
    'ranges_time' => '{ranges_legend},rangeWizardTime,buttonAllCaption,buttonPosition,buttonAllPosition,loadOutOfRangeData;',
    'coordinate_system_nominal' => '{coordinate_system_legend},swapAxes,xshow,xLabelText,xLabelPosition,yshow,yInverted,yLabelText,yLabelPosition,y2show,y2Inverted,y2LabelText,y2LabelPosition;',
    'coordinate_system_time' => '{coordinate_system_legend},swapAxes,xshow,xLabelText,xLabelPosition,xTimeFormat,yshow,yInverted,yLabelText,yLabelPosition,y2show,y2Inverted,y2LabelText,y2LabelPosition;',
    'watermark' => '{watermark_legend:hide},image,imageMaxHeight,imageMaxWidth,imageMarginTop,imageMarginLeft,imageOpacity;',
    'expert' => '{expert_legend:hide},zoom;',
    'publish' => '{publish_legend},published;'
];

/**
 * Table tl_c4g_visualization_chart
 */
$GLOBALS['TL_DCA']['tl_c4g_visualization_chart'] = array
(

	// Config
	'config' => array
	(
	    'label'                       => $GLOBALS['TL_CONFIG']['websiteTitle'],
	    'dataContainer'               => 'Table',
		'enableVersioning'            => true,
//	    'onload_callback'			  => array(array('tl_c4g_visualization_chart', 'updateDCA')),
//	    'onsubmit_callback'           => array(array('tl_c4g_visualization_chart', 'onSubmit')),
//		'ondelete_callback'			  => array(array('tl_c4g_visualization_chart', 'onDeleteForum')),
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
            'panelLayout'             => 'search,limit',
            'headerFields'            => array('id', 'backendtitle'),
		),
		'label' => array
		(
			'fields'                  => array('id', 'backendtitle'),
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
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
//				'button_callback'     => array('tl_c4g_visualization_chart', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
        '__selector__'                => ['xValueCharacter'],
	    'default'                     => $palettes['general']
	),

    'subpalettes' => [
        'xValueCharacter_1' => $palettes['elements'] . $palettes['ranges_nominal'] .
            $palettes['coordinate_system_nominal'] . $palettes['watermark'] . $palettes['expert'] . $palettes['publish'],
        'xValueCharacter_2' => $palettes['elements'] . $palettes['ranges_time'] .
            $palettes['coordinate_system_time'] . $palettes['watermark'] . $palettes['expert'] . $palettes['publish'],
    ],

	// Fields
	'fields' => array
	(
        'id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['id'],
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'published' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['published'],
            'default'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array(),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'backendtitle' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['backendtitle'],
            'inputType'               => 'text',
            'search'                  => 'true',
            'sorting'                 => 'true',
            'default'                 => '',
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255 ),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'zoom' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['zoom'],
            'default'                 => false,
            'inputType'               => 'checkbox',
            'eval'                    => array(),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'xValueCharacter' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['xValueCharacter'],
            'default'                 => '1',
            'inputType'               => 'select',
            'options_callback'        => ['tl_c4g_visualization_chart', 'loadXValueCharacterOptions'],
            'eval'                    => [
                'submitOnChange' => true
            ],
            'sql'                     => "char(1) NOT NULL default '1'"
        ),
        'swapAxes' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['swapAxes'],
            'default'                 => false,
            'inputType'               => 'checkbox',
            'eval'                    => [],
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'xshow' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['xshow'],
            'default'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => [
                'tl_class'            => 'clr'
            ],
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'xTimeFormat' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['xTimeFormat'],
            'default'                 => 'd.m.Y',
            'inputType'               => 'text',
            'eval'                    => [
                'tl_class'            => 'clr'
            ],
            'sql'                     => "varchar(255) NOT NULL default 'd.m.Y'"
        ),
        'xRotate' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['xRotate'],
            'inputType'               => 'text',
            'eval'                    =>
            [
                'maxlength'=>10,
                'rgxp'=>'digit'
            ],
            'sql'                     => "int(10) signed NOT NULL default '0'"
        ),
        'xLabelText' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['xLabelText'],
            'inputType'               => 'text',
            'eval'                    => [
                'tl_class'            => 'w50'
            ],
            'sql'                     => "varchar(255) signed NOT NULL default ''"
        ),
        'xLabelPosition' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['xLabelPosition'],
            'inputType'               => 'select',
            'options_callback'        => ['tl_c4g_visualization_chart', 'loadLabelPositionOptions'],
            'eval'                    => [
                'tl_class'            => 'w50'
            ],
            'default'                 => '1',
            'sql'                     => "char(1) NOT NULL default '1'"
        ),
        'yshow' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['yshow'],
            'default'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => [
                'tl_class'            => 'clr'
            ],
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'yInverted' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['yInverted'],
            'default'                 => false,
            'inputType'               => 'checkbox',
            'eval'                    => [
                'tl_class'            => 'clr'
            ],
            'sql'                     => "char(1) NOT NULL default '0'"
        ),
//        'yScale' => array
//        (
//            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['yInverted'],
//            'default'                 => '1',
//            'inputType'               => 'select',
//            'eval'                    => [],
//            'options_callback'        => ['tl_c4g_visualization_chart', 'loadScaleOptions'],
//            'sql'                     => "char(1) NOT NULL default '1'"
//        ),
        'yLabelText' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['yLabelText'],
            'inputType'               => 'text',
            'eval'                    => [
                'tl_class'            => 'w50'
            ],
            'sql'                     => "varchar(255) signed NOT NULL default ''"
        ),
        'yLabelPosition' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['yLabelPosition'],
            'inputType'               => 'select',
            'options_callback'        => ['tl_c4g_visualization_chart', 'loadLabelPositionOptions'],
            'eval'                    => [
                'tl_class'            => 'w50'
            ],
            'default'                 => '1',
            'sql'                     => "char(1) NOT NULL default '1'"
        ),
        'y2show' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['y2show'],
            'default'                 => false,
            'inputType'               => 'checkbox',
            'eval'                    => [
                'tl_class'            => 'clr'
            ],
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'y2Inverted' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['y2Inverted'],
            'default'                 => false,
            'inputType'               => 'checkbox',
            'eval'                    => [
                'tl_class'            => 'clr'
            ],
            'sql'                     => "char(1) NOT NULL default '0'"
        ),
        'y2LabelText' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['y2LabelText'],
            'inputType'               => 'text',
            'eval'                    => [
                'tl_class'            => 'w50'
            ],
            'sql'                     => "varchar(255) signed NOT NULL default ''"
        ),
        'y2LabelPosition' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['y2LabelPosition'],
            'inputType'               => 'select',
            'options_callback'        => ['tl_c4g_visualization_chart', 'loadLabelPositionOptions'],
            'eval'                    => [
                'tl_class'            => 'w50'
            ],
            'default'                 => '1',
            'sql'                     => "char(1) NOT NULL default '1'"
        ),

        'elementWizard' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['elementWizard'],
            'inputType'               => 'multiColumnWizard',
            'save_callback'           => array(array('tl_c4g_visualization_chart', 'saveElements')),
            'load_callback'           => array(array('tl_c4g_visualization_chart', 'loadElements')),
            'eval'                    => array
            (
                'columnFields' => array
                (
                    'elementId' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['elementId'],
                        'inputType'               => 'select',
                        'foreignKey'              => 'tl_c4g_visualization_chart_element.backendtitle',
                        'eval'                    => array('includeBlankOption' => true),
                    ),
                ),
                'doNotSaveEmpty'    => true
            ),
        ),
        'image' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['image'],
            'inputType'               => 'fileTree',
            'eval'                    => array
            (
                'fieldType' => 'radio',
                'files' => true,
                'filesOnly' => true,
                'tl_class' => 'clr',
                'extensions' => $GLOBALS['TL_CONFIG']['validImageTypes']
            ),
            'save_callback'           => array(array('tl_c4g_visualization_chart', 'changeFileBinToUuid')),
            'sql'                     => "varchar(255) NULL default ''"
        ),
        'imageMaxHeight' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['imageMaxHeight'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array(
                'maxlength'=>10,
                'rgxp'=>'natural',
                'tl_class' => 'w50'
            ),
            'default'                 => '200',
            'sql'                     => "int(10) unsigned NOT NULL default '200'"
        ),
        'imageMaxWidth' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['imageMaxWidth'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array(
                'maxlength'=>10,
                'rgxp'=>'natural',
                'tl_class' => 'w50'
            ),
            'default'                 => '200',
            'sql'                     => "int(10) unsigned NOT NULL default '200'"
        ),
        'imageMarginTop' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['imageMarginTop'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array(
                'maxlength'=>10,
                'rgxp'=>'natural',
                'tl_class' => 'w50'
            ),
            'default'                 => '50',
            'sql'                     => "int(10) unsigned NOT NULL default '50'"
        ),
        'imageMarginLeft' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['imageMarginLeft'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array(
                'maxlength'=>10,
                'rgxp'=>'natural',
                'tl_class' => 'w50'
            ),
            'default'                 => '100',
            'sql'                     => "int(10) unsigned NOT NULL default '100'"
        ),
        'imageOpacity' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['imageOpacity'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array(
                'maxlength'=>10,
                'rgxp'=>'natural',
                'tl_class' => 'clr'
            ),
            'default'                 => '80',
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'rangeWizardNominal' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['rangeWizardNominal'],
            'inputType'               => 'multiColumnWizard',
            'save_callback'           => array(array('tl_c4g_visualization_chart', 'saveRanges')),
            'load_callback'           => array(array('tl_c4g_visualization_chart', 'loadRanges')),
            'eval'                    => array
            (
                'columnFields' => array
                (
                    'name' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['name'],
                        'inputType'               => 'text'
                    ),
                    'fromX' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['fromX'],
                        'inputType'               => 'text',
                        'eval'                    => array('rgxp' => 'digit'),
                    ),
                    'toX' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['toX'],
                        'inputType'               => 'text',
                        'eval'                    => array('rgxp' => 'digit'),
                    ),
                    'defaultRange' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['defaultRange'],
                        'inputType'               => 'checkbox',
                        'default'                 => '0',
                    ),
                ),
                'doNotSaveEmpty'    => true,
            ),
        ),
        'rangeWizardTime' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['rangeWizardTime'],
            'inputType'               => 'multiColumnWizard',
            'save_callback'           => array(array('tl_c4g_visualization_chart', 'saveRanges')),
            'load_callback'           => array(array('tl_c4g_visualization_chart', 'loadRanges')),
            'eval'                    => array
            (
                'columnFields' => array
                (
                    'name' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['name'],
                        'inputType'               => 'text'
                    ),
                    'fromX' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['fromX'],
                        'inputType'               => 'text',
                        'eval'                    => array('datepicker'=>true, 'tl_class'=>'w50 wizard')
                    ),
                    'toX' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['toX'],
                        'inputType'               => 'text',
                        'eval'                    => array('datepicker'=>true, 'tl_class'=>'w50 wizard')
                    ),
                    'defaultRange' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['defaultRange'],
                        'inputType'               => 'checkbox',
                        'default'                 => '0',
                    ),
                ),
                'doNotSaveEmpty'    => true,
            ),
        ),
        'buttonAllCaption' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['buttonAllCaption'],
            'inputType'               => 'text',
            'default'                 => '',
            'eval'                    => array
            (
                'mandatory' => false,
                'maxlength' => 255
            ),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'buttonPosition' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['buttonPosition'],
            'inputType'               => 'select',
            'default'                 => '1',
            'options_callback'         => ['tl_c4g_visualization_chart', 'loadButtonPositionOptions'],
            'eval'                    => array(
                'tl_class' => 'w50'
            ),
            'sql'                     => "char(1) NOT NULL default '1'"
        ),
        'buttonAllPosition' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['buttonAllPosition'],
            'inputType'               => 'select',
            'default'                 => '2',
            'options_callback'         => ['tl_c4g_visualization_chart', 'loadButtonAllPositionOptions'],
            'eval'                    => array
            (
                'tl_class' => 'w50'
            ),
            'sql'                     => "char(1) NOT NULL default '2'"
        ),
        'loadOutOfRangeData' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['loadOutOfRangeData'],
            'default'                 => false,
            'inputType'               => 'checkbox',
            'eval'                    => array(
                'tl_class' => 'clr'
            ),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
    )
);

/**
 * Class tl_c4g_visualization_chart
 */
class tl_c4g_visualization_chart extends \Backend
{

    public function loadButtonAllPositionOptions(DataContainer $dc) {
        return [
            '1' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_as_first'],
            '2' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_as_last']
        ];
    }

    public function loadButtonPositionOptions(DataContainer $dc) {
        return [
            '1' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_top_left'],
            '2' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_top_middle'],
            '3' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_top_right'],
            '4' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_bottom_left'],
            '5' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_bottom_middle'],
            '6' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_bottom_right'],
        ];
    }

    public function loadLabelPositionOptions(DataContainer $dc) {
        return [
            '1' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_inner_right_up'],
            '2' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_inner_middle'],
            '3' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_inner_left_down'],
            '4' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_outer_right_up'],
            '5' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_outer_middle'],
            '6' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_outer_left_down']
        ];
    }

    public function loadXValueCharacterOptions(DataContainer $dc) {
        return [
            '1' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_nominal_values'],
            '2' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_temporal_values'],
        ];
    }

//    public function loadScaleOptions(DataContainer $dc) {
//        return [
//            '1' => 'innen rechts/oben',
//            '2' => 'innen mittig',
//            '3' => 'innen links/unten',
//            '4' => 'außen rechts/oben',
//            '5' => 'außen mittig',
//            '6' => 'außen links/unten'
//        ];
//    }

    /**
     * @param $value
     * @param DataContainer $dc
     * @return null
     */
    public function saveElements($value, DataContainer $dc)  {
        $inputs = unserialize($value);
        if (is_array($inputs) === true) {
            $database = \Contao\Database::getInstance();
            $database->prepare(
                "DELETE FROM tl_c4g_visualization_chart_element_relation WHERE chartId = ?")->execute($dc->activeRecord->id);
            foreach($inputs as $input) {
                if (empty($input) === true || $input['elementId'] === '') {
                    continue;
                }
                $stmt = $database->prepare(
                    "INSERT INTO tl_c4g_visualization_chart_element_relation (chartId, elementId) ".
                    "VALUES (?, ?)");
                $stmt->execute($dc->activeRecord->id, $input['elementId']);
            }
        }
        return null;
    }

    /**
     * @param $value
     * @param DataContainer $dc
     * @return array
     */
    public function loadElements($value, DataContainer $dc) : array {
        $database = \Contao\Database::getInstance();
        $stmt = $database->prepare(
            "SELECT elementId FROM tl_c4g_visualization_chart_element_relation ".
            "INNER JOIN tl_c4g_visualization_chart_element ON tl_c4g_visualization_chart_element_relation.elementId=".
            "tl_c4g_visualization_chart_element.id ".
            "WHERE chartId = ?");
        $result = $stmt->execute($dc->activeRecord->id);
        return $result->fetchAllAssoc();
    }

    /**
     * @param $value
     * @param DataContainer $dc
     * @return null
     */
    public function saveRanges($value, DataContainer $dc)  {
        $inputs = unserialize($value);
        if (is_array($inputs) === true) {
            $database = \Contao\Database::getInstance();
            $database->prepare(
                "DELETE FROM tl_c4g_visualization_chart_range WHERE chartId = ?")->execute($dc->activeRecord->id);
            foreach($inputs as $input) {
                if (empty($input) === true || $input['name'] === '' || $input['fromX'] === '' || $input['toX'] === '') {
                    continue;
                }
                if ($input['defaultRange'] !== '1') {
                    $input['defaultRange'] = '0';
                }

                if ($dc->activeRecord->xValueCharacter === '2') {

                    $dateTime = new \DateTime();

                    $array = explode('/', $input['fromX']);

                    $month = $array[0];
                    $day = $array[1];
                    $year = $array[2];
                    $dateTime->setDate($year, $month, $day);
                    $input['fromX'] = floatval(strtotime('today', $dateTime->getTimestamp()));

                    $array = explode('/', $input['toX']);
                    $month = $array[0];
                    $day = $array[1];
                    $year = $array[2];
                    $dateTime->setDate($year, $month, $day);
                    $input['toX'] = floatval(strtotime('today', $dateTime->getTimestamp()));
                }


                $stmt = $database->prepare(
                    "INSERT INTO tl_c4g_visualization_chart_range (chartId, name, fromX, toX, defaultRange) ".
                    "VALUES (?, ?, ?, ?, ?)");
                $stmt->execute($dc->activeRecord->id, $input['name'], $input['fromX'], $input['toX'], $input['defaultRange']);
            }
        }
        return null;
    }

    /**
     * @param $value
     * @param DataContainer $dc
     * @return array
     * @throws Exception
     */
    public function loadRanges($value, DataContainer $dc) : array {
        $database = \Contao\Database::getInstance();
        $stmt = $database->prepare(
            "SELECT name, fromX, toX, defaultRange FROM tl_c4g_visualization_chart_range WHERE chartId = ?");
        $result = $stmt->execute($dc->activeRecord->id)->fetchAllAssoc();
        if ($dc->activeRecord->xValueCharacter === '2') {
            $dateTime = new DateTime();
            foreach ($result as $key => $value) {
                if ($value['fromX'] === 0.0) {
                    $value['fromX'] = time();
                }
                $dateTime->setTimestamp(intval($value['fromX']));
                $year = $dateTime->format('Y');
                $month = $dateTime->format('m');
                $day = $dateTime->format('d');
                $result[$key]['fromX'] = "$month/$day/$year";

                if ($value['toX'] === 0.0) {
                    $value['toX'] = time();
                }
                $dateTime->setTimestamp(intval($value['toX']));
                $year = $dateTime->format('Y');
                $month = $dateTime->format('m');
                $day = $dateTime->format('d');
                $result[$key]['toX'] = "$month/$day/$year";
            }
        }
        return $result;
    }

    public function changeFileBinToUuid($fieldValue, DataContainer $dc) {
        return \StringUtil::binToUuid($fieldValue);
    }
}
