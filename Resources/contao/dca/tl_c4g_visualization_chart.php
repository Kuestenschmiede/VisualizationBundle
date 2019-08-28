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
		'default'                     => '{general_legend},backendtitle,frontendtitle,zoom;'.
                                         '{element_legend},elementWizard;'.
										 '{watermark_legend},image,imageMaxHeight,imageMaxWidth,imageMarginTop,imageMarginLeft,imageOpacity;'.
										 '{ranges_legend},rangeWizard,buttonAllCaption,buttonPosition,buttonAllPosition;'.
										 '{coordinate_system_legend:hide},swapAxes,xshow,xType,xRotate,xLabelText,xLabelPosition,yshow,yInverted,yLabelText,yLabelPosition,y2show,y2Inverted,y2LabelText,y2LabelPosition;'.
										 '{publish_legend},published;'
	),

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
        'swapAxes' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['swapAxes'],
            'default'                 => true,
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
        'xType' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['xType'],
            'default'                 => '1',
            'inputType'               => 'select',
            'options_callback'        => ['tl_c4g_visualization_chart', 'loadXTypeOptions'],
            'eval'                    => [],
            'sql'                     => "char(1) NOT NULL default '1'"
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
                'doNotSaveEmpty'    => true,
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
            ),
            'default'                 => '200',
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'imageMaxWidth' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['imageMaxWidth'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array(
                'maxlength'=>10,
                'rgxp'=>'natural',
            ),
            'default'                 => '200',
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'imageMarginTop' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['imageMarginTop'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array(
                'maxlength'=>10,
                'rgxp'=>'natural',
            ),
            'default'                 => '5',
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'imageMarginLeft' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['imageMarginLeft'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array(
                'maxlength'=>10,
                'rgxp'=>'natural',
            ),
            'default'                 => '10',
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'imageOpacity' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['imageOpacity'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array(
                'maxlength'=>10,
                'rgxp'=>'natural'
            ),
            'default'                 => '80',
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'rangeWizard' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['rangeWizard'],
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
                'mandatory' => false,
                'tl_class' => 'w50'
            ),
            'sql'                     => "char(1) NOT NULL default '2'"
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
            '1' => 'als erster',
            '2' => 'als letzter'
        ];
    }

    public function loadButtonPositionOptions(DataContainer $dc) {
        return [
            '1' => 'Oben Links',
            '2' => 'Oben Mittig',
            '3' => 'Oben Rechts',
            '4' => 'Unten Links',
            '5' => 'Unten Mittig',
            '6' => 'Unten Rechts',
        ];
    }

    public function loadXTypeOptions(DataContainer $dc) {
        return [
            '1' => 'Tatsächliche Werte',
            '2' => 'Zeit',
            '3' => 'Kategorien'
        ];
    }

    public function loadLabelPositionOptions(DataContainer $dc) {
        return [
            '1' => 'innen rechts/oben',
            '2' => 'innen mittig',
            '3' => 'innen links/unten',
            '4' => 'außen rechts/oben',
            '5' => 'außen mittig',
            '6' => 'außen links/unten'
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
     */
    public function loadRanges($value, DataContainer $dc) : array {
        $database = \Contao\Database::getInstance();
        $stmt = $database->prepare(
            "SELECT name, fromX, toX, defaultRange FROM tl_c4g_visualization_chart_range WHERE chartId = ?");
        $result = $stmt->execute($dc->activeRecord->id);
        return $result->fetchAllAssoc();
    }

    public function changeFileBinToUuid($fieldValue, DataContainer $dc) {
        return \StringUtil::binToUuid($fieldValue);
    }
}
