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
            'headerFields'            => array('backendtitle'),
		),
		'label' => array
		(
			'fields'                  => array('backendtitle'),
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
										 '{ranges_legend},rangeWizard,buttonAllCaption,buttonPosition;'.
										 '{x_legend:hide},xshow;'.
										 '{y_legend:hide},yshow;'.
										 '{x2_legend:hide},x2show;'.
										 '{y2_legend:hide},y2show;'.
										 '{publish_legend},published;'
	),

	// Fields
	'fields' => array
	(
        'id' => array
        (
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
        'xshow' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['xshow'],
            'default'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array(),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'x2show' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['x2show'],
            'default'                 => false,
            'inputType'               => 'checkbox',
            'eval'                    => array(),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'yshow' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['yshow'],
            'default'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array(),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'y2show' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['y2show'],
            'default'                 => false,
            'inputType'               => 'checkbox',
            'eval'                    => array(),
            'sql'                     => "char(1) NOT NULL default ''"
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
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255 ),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'buttonPosition' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['buttonPosition'],
            'inputType'               => 'select',
            'default'                 => '1',
            'options_callback'         => ['tl_c4g_visualization_chart', 'loadButtonPositionOptions'],
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255 ),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
    )
);

/**
 * Class tl_c4g_visualization_chart
 */
class tl_c4g_visualization_chart extends \Backend
{

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
