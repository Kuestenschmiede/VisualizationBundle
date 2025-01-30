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
use con4gis\CoreBundle\Classes\DCA\Fields\CheckboxField;
use con4gis\CoreBundle\Classes\DCA\Fields\ColorPickerField;
use con4gis\CoreBundle\Classes\DCA\Fields\DatePickerField;
use con4gis\CoreBundle\Classes\DCA\Fields\DigitField;
use con4gis\CoreBundle\Classes\DCA\Fields\IdField;
use con4gis\CoreBundle\Classes\DCA\Fields\ImageField;
use con4gis\CoreBundle\Classes\DCA\Fields\MultiColumnField;
use con4gis\CoreBundle\Classes\DCA\Fields\NaturalField;
use con4gis\CoreBundle\Classes\DCA\Fields\SelectField;
use con4gis\CoreBundle\Classes\DCA\Fields\SQLField;
use con4gis\CoreBundle\Classes\DCA\Fields\TextField;
use con4gis\VisualizationBundle\Classes\Labels\LabelPosition;
use Contao\DataContainer;

$palettes = [
    '__selector__' => ['showSubchart'],
    'general' => '{general_legend},backendtitle,xValueCharacter,',
    'elements' => 'elementWizard',
    'ranges_nominal' => ';{ranges_legend},rangeWizardNominal,buttonAllCaption,buttonPosition,buttonAllPosition,loadOutOfRangeData,decimalPoints',
    'ranges_time' => ';{ranges_legend},rangeWizardTime,buttonAllCaption,buttonPosition,buttonAllPosition,xLabelCountAll,xTimeFormatAll,loadOutOfRangeData,decimalPoints',
    'coordinate_system_nominal' => ';{coordinate_system_legend},swapAxes,xshow,xLabelText,xLabelPosition,xRotate,xLabelCount,yshow,yInverted,yLabelText,yLabelPosition,yFormat,yLabelCount,yMin,yMax,y2show,y2Inverted,y2LabelText,y2LabelPosition,y2Format,y2LabelCount,y2Min,y2Max',
    'coordinate_system_time' => ';{coordinate_system_legend},swapAxes,xshow,xLabelText,xLabelPosition,xRotate,xTimeFormat,xTickMode,xLabelCount,yshow,yInverted,yLabelText,yLabelPosition,yFormat,yLabelCount,yMin,yMax,y2show,y2Inverted,y2LabelText,y2LabelPosition,y2Format,y2LabelCount,y2Min,y2Max',
    'watermark' => ';{watermark_legend:hide},image,imageMaxHeight,imageMaxWidth,imageMarginTop,imageMarginLeft,imageOpacity',
    'expert' => ';{expert_legend:hide},zoom,points,legend,tooltips,labels,labelColor,oneLabelPerElement,cssClass,showEmptyYValues,showSubchart,gridX,gridY',
    'publish' => ';{publish_legend},published'
];

/**
 * Table tl_c4g_visualization_chart
 */
$dca = new DCA('tl_c4g_visualization_chart');

if (!$GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['subpalettes']) {
    $GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['subpalettes'] = [];
}

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['subpalettes'] = [
    'showSubchart' => "subchartHeight,subchartShowXAxis",
];

$dca->list()->sorting()->headerFields(['id', 'backendtitle']);
$dca->list()->label()->fields(['id', 'backendtitle']);
$dca->list()->addRegularOperations($dca);

$dca->palette()->selector(['xValueCharacter','showSubchart']);
$dca->palette()->default($palettes['general']);
$dca->palette()->subPalette(
    'xValueCharacter',
    '1',
    $palettes['elements'] . $palettes['ranges_nominal'] .
    $palettes['coordinate_system_nominal'] . $palettes['watermark'] . $palettes['expert'] . $palettes['publish']);
$dca->palette()->subPalette(
    'xValueCharacter',
    '2',
    $palettes['elements'] . $palettes['ranges_time'] .
    $palettes['coordinate_system_time'] . $palettes['watermark'] . $palettes['expert'] . $palettes['publish']);

$id = new IdField('id', $dca);

$tstamp = new NaturalField('tstamp', $dca);
$backendTitle = new TextField('backendtitle', $dca);
$backendTitle->search()->sorting();

$xValueCharacter = new SelectField('xValueCharacter', $dca);
$xValueCharacter->optionsCallback('tl_c4g_visualization_chart', 'loadXValueCharacterOptions')
    ->eval()->submitOnChange();

$elementWizard = new MultiColumnField('elementWizard', $dca);
$elementWizard->saveCallback('tl_c4g_visualization_chart', 'saveElements')
    ->loadCallback('tl_c4g_visualization_chart', 'loadElements');
$elementId = new SelectField('elementId', $dca, $elementWizard);
$elementId->foreignKey('tl_c4g_visualization_chart_element', 'backendtitle')->eval()->includeBlankOption();

$rangeWizardNominal = new MultiColumnField('rangeWizardNominal', $dca);
$rangeWizardNominal->saveCallback('tl_c4g_visualization_chart', 'saveRanges')
    ->loadCallback('tl_c4g_visualization_chart', 'loadRanges');
$name = new TextField('name', $dca, $rangeWizardNominal);
$fromX = new TextField('fromX', $dca, $rangeWizardNominal);
$toX = new TextField('toX', $dca, $rangeWizardNominal);
$defaultRange = new CheckboxField('defaultRange', $dca, $rangeWizardNominal);
$yMinRange = new NaturalField('yMin', $dca, $rangeWizardNominal);
$yMaxRange = new NaturalField('yMax', $dca, $rangeWizardNominal);
$y2MinRange = new NaturalField('y2Min', $dca, $rangeWizardNominal);
$y2MaxRange = new NaturalField('y2Max', $dca, $rangeWizardNominal);

$rangeWizardTime = new MultiColumnField('rangeWizardTime', $dca);
$rangeWizardTime->saveCallback('tl_c4g_visualization_chart', 'saveRanges')
    ->loadCallback('tl_c4g_visualization_chart', 'loadRanges');
$name = new TextField('name', $dca, $rangeWizardTime);
$fromX = new DatePickerField('fromX', $dca, $rangeWizardTime);
$toX = new DatePickerField('toX', $dca, $rangeWizardTime);
$defaultRange = new CheckboxField('defaultRange', $dca, $rangeWizardTime);
$yMinRange = new NaturalField('yMin', $dca, $rangeWizardTime);
$yMaxRange = new NaturalField('yMax', $dca, $rangeWizardTime);
$y2MinRange = new NaturalField('y2Min', $dca, $rangeWizardTime);
$y2MaxRange = new NaturalField('y2Max', $dca, $rangeWizardTime);

$buttonAllCaption = new TextField('buttonAllCaption', $dca);

$xLabelCountAll = new NaturalField('xLabelCountAll', $dca);
$xLabelCountAll->default('1')->sql("int(10) unsigned NOT NULL default '1'")
    ->eval()->maxlength(10)
    ->regEx('natural')
    ->class('clr');
$xTimeFormatAll = new TextField('xTimeFormatAll', $dca);
$xTimeFormatAll->default('d.m.Y')->sql("varchar(255) NOT NULL default 'd.m.Y'")->eval()->class('clr');

$buttonPosition = new SelectField('buttonPosition', $dca);
$buttonPosition->optionsCallback('tl_c4g_visualization_chart', 'loadButtonPositionOptions')
    ->eval()->class('w50');
$buttonAllPosition = new SelectField('buttonAllPosition', $dca);
$buttonAllPosition->optionsCallback('tl_c4g_visualization_chart', 'loadButtonAllPositionOptions')
    ->default('2')->sql("char(1) NOT NULL default '2'")->eval()->class('w50');
$loadOutOfRangeData = new CheckboxField('loadOutOfRangeData', $dca);

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['fields']['showEmptyYValues'] =  [
    'inputType' => "checkbox",
    'default' => '1',
    'eval' => ['tl_class' => "clr"],
    'sql' => "char(1) NOT NULL DEFAULT '1'"
];

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['fields']['yRotate'] =  [
    'inputType' => "text",
    'default' => '0',
    'eval' => ['tl_class' => "clr", 'maxlength' => 10],
    'sql' => "int(10) signed NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['fields']['yFormat'] =  [
    'inputType' => "text",
    'default' => '',
    'eval' => ['tl_class' => "clr", 'maxlength' => 20, 'doNotTrim' => true],
    'sql' => "varchar(20) NOT NULL DEFAULT ''"
];

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['fields']['yLabelCount'] =  [
    'inputType' => "text",
    'default' => '0',
    'eval' => ['tl_class' => "clr", 'maxlength' => 10],
    'sql' => "int(10) signed NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['fields']['y2Format'] =  [
    'inputType' => "text",
    'default' => '',
    'eval' => ['tl_class' => "clr", 'maxlength' => 20, 'doNotTrim' => true],
    'sql' => "varchar(20) NOT NULL DEFAULT ''"
];

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['fields']['y2LabelCount'] =  [
    'inputType' => "text",
    'default' => '0',
    'eval' => ['tl_class' => "clr", 'maxlength' => 10],
    'sql' => "int(10) signed NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['fields']['showSubchart'] =  [
    'inputType' => "checkbox",
    'default' => '',
    'eval' => ['tl_class' => "clr", 'submitOnChange' => true],
    'sql' => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['fields']['subchartHeight'] =  [
    'inputType' => "text",
    'default' => '20',
    'eval' => ['tl_class' => "clr", 'maxlength' => 10],
    'sql' => "int(10) signed NOT NULL default '20'"
];

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['fields']['subchartShowXAxis'] =  [
    'inputType' => "checkbox",
    'default' => '1',
    'eval' => ['tl_class' => "clr"],
    'sql' => "char(1) NOT NULL DEFAULT ''"
];

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['fields']['gridX'] =  [
    'inputType' => "checkbox",
    'default' => '',
    'eval' => ['tl_class' => "clr"],
    'sql' => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['fields']['gridY'] =  [
    'inputType' => "checkbox",
    'default' => '',
    'eval' => ['tl_class' => "clr"],
    'sql' => "char(1) NOT NULL default ''"
];

$decimalPoints = new NaturalField('decimalPoints', $dca);
$decimalPoints->default('0')->sql("int(10) unsigned NOT NULL default '0'")
    ->eval()->maxlength(10)->class('clr');

$swapAxes = new CheckboxField('swapAxes', $dca);
$xShow = new CheckboxField('xshow', $dca);
$xShow->default(true);
$xLabelText = new TextField('xLabelText', $dca);
$xLabelText->loadCallback('tl_c4g_visualization_chart', 'loadXLabelText');
$xTimeFormat = new TextField('xTimeFormat', $dca);
$xTimeFormat->default('d.m.Y')->sql("varchar(255) NOT NULL default 'd.m.Y'")->eval()->class('clr');
$xTickMode = new SelectField('xTickMode', $dca);
$xTickMode->optionsCallback('tl_c4g_visualization_chart', 'loadXTickModeOptions');
$xTickMode->default('nth');
$xTickMode->sql('varchar(7) NOT NULL default "nth"');
$xLabelCount = new NaturalField('xLabelCount', $dca);
$xLabelCount->default('1')->sql("int(10) unsigned NOT NULL default '1'")
    ->eval()->maxlength(10)
        ->regEx('natural')
        ->class('clr');
$xRotate = new DigitField('xRotate', $dca);
$xRotate->eval()->maxlength(10)
    ->class('clr');
$xLabelText->eval()->class('w50');
$xLabelPosition = new SelectField('xLabelPosition', $dca);
$xLabelPosition->optionsCallback('tl_c4g_visualization_chart', 'loadXLabelPositionOptions');
$xLabelPosition->eval()->class('w50');
$xLabelPosition->sql("varchar(20) NOT NULL default ". LabelPosition::HORIZONTAL_INNER_RIGHT);
$xLabelPosition->default(LabelPosition::HORIZONTAL_INNER_RIGHT);

$yShow = new CheckboxField('yshow', $dca);
$yShow->default(true);
$yInverted = new CheckboxField('yInverted', $dca);
$yLabelText = new TextField('yLabelText', $dca);
$yLabelText->loadCallback('tl_c4g_visualization_chart', 'loadYLabelText');
$yLabelText->eval()->class('w50');
$yLabelPosition = new SelectField('yLabelPosition', $dca);
$yLabelPosition->optionsCallback('tl_c4g_visualization_chart', 'loadYLabelPositionOptions');
$yLabelPosition->eval()->class('w50');
$yLabelPosition->sql("varchar(20) NOT NULL default ". LabelPosition::VERTICAL_INNER_TOP);
$yLabelPosition->default(LabelPosition::VERTICAL_INNER_TOP);
$yMin = new NaturalField('yMin', $dca);
$yMin->default(null)->sql("int unsigned NULL")
    ->eval()->maxlength(10)->class('w50');
$yMax = new NaturalField('yMax', $dca);
$yMax->default(null)->sql("int unsigned NULL")
    ->eval()->maxlength(10)->class('w50');
$y2Show = new CheckboxField('y2show', $dca);
$y2Show->default(false);
$y2Inverted = new CheckboxField('y2Inverted', $dca);
$y2LabelText = new TextField('y2LabelText', $dca);
$y2LabelText->loadCallback('tl_c4g_visualization_chart', 'loadY2LabelText');
$y2LabelText->eval()->class('w50');
$y2LabelPosition = new SelectField('y2LabelPosition', $dca);
$y2LabelPosition->optionsCallback('tl_c4g_visualization_chart', 'loadYLabelPositionOptions');
$y2LabelPosition->eval()->class('w50');
$y2LabelPosition->sql("varchar(20) NOT NULL default ". LabelPosition::VERTICAL_INNER_TOP);
$y2LabelPosition->default(LabelPosition::VERTICAL_INNER_TOP);
$y2Min = new NaturalField('y2Min', $dca);
$y2Min->default(null)->sql("int unsigned NULL")
    ->eval()->maxlength(10)->class('w50');
$y2Max = new NaturalField('y2Max', $dca);
$y2Max->default(null)->sql("int unsigned NULL")
    ->eval()->maxlength(10)->class('w50');

$image = new ImageField('image', $dca);
$image->saveCallback('tl_c4g_visualization_chart', 'changeFileBinToUuid');

$imageMaxHeight = new NaturalField('imageMaxHeight', $dca);
$imageMaxHeight->default('200')->sql("int(10) unsigned NOT NULL default '200'")
    ->eval()->maxlength(10)->class('w50');
$imageMaxWidth = new NaturalField('imageMaxWidth', $dca);
$imageMaxWidth->default('200')->sql("int(10) unsigned NOT NULL default '200'")
    ->eval()->maxlength(10)->class('w50');
$imageMarginTop = new NaturalField('imageMarginTop', $dca);
$imageMarginTop->default('50')->sql("int(10) unsigned NOT NULL default '50'")
    ->eval()->maxlength(10)->class('w50');
$imageMarginLeft = new NaturalField('imageMarginLeft', $dca);
$imageMarginLeft->default('100')->sql("int(10) unsigned NOT NULL default '100'")
    ->eval()->maxlength(10)->class('w50');
$imageOpacity = new NaturalField('imageOpacity', $dca);
$imageOpacity->default('80')->sql("int(10) unsigned NOT NULL default '80'")
    ->eval()->maxlength(10)->class('clr');


$zoom = new CheckboxField('zoom', $dca);

$points = new CheckboxField('points', $dca);
$points->default(true);
$points->sql("char(1) NOT NULL default '1'");

$legend = new CheckboxField('legend', $dca);
$legend->default(true);
$legend->sql("char(1) NOT NULL default '1'");

$tooltips = new CheckboxField('tooltips', $dca);
$tooltips->default(true);
$tooltips->sql("char(1) NOT NULL default '1'");

$labels = new CheckboxField('labels', $dca);
$labels->default(false);
$labels->sql("char(1) NOT NULL default '0'");

$labelColor = new ColorPickerField('labelColor', $dca);
$labelColor->default('000000');
$labelColor->sql("varchar(64) NOT NULL default '000000'");

$oneLabelPerElement = new CheckboxField('oneLabelPerElement', $dca);
$oneLabelPerElement->default(false);
$oneLabelPerElement->sql("char(1) NOT NULL default '0'");

$published = new CheckboxField('published', $dca);
$published->default(true);

$importId = new SQLField("importId", $dca, "int(20) unsigned NOT NULL default '0'");
$importId->eval()->doNotCopy(true);

$cssClass = new TextField('cssClass', $dca);

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart']['list']['sorting'] = [
    'mode'                    => 2,
    'fields'                  => ['backendtitle'],
    'panelLayout'             => 'filter;sort,search,limit',
    'headerFields'            => ['backendtitle'],
    'flag'                    => 1,
    'icon'                    => ''
];


/**
 * Class tl_c4g_visualization_chart
 */
class tl_c4g_visualization_chart extends \Contao\Backend
{

    public function loadButtonAllPositionOptions(DataContainer $dc) {
        return [
            '1' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_as_first'],
            '2' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_as_last']
        ];
    }

    public function loadButtonPositionOptions(DataContainer $dc) {
        return [
            '1' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_top_left'],
            '2' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_top_middle'],
            '3' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_top_right'],
            '4' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_bottom_left'],
            '5' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_bottom_middle'],
            '6' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_bottom_right'],
        ];
    }

    public function loadXLabelPositionOptions(DataContainer $dc) {
        return [
            LabelPosition::HORIZONTAL_INNER_RIGHT => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_inner_right'],
            LabelPosition::HORIZONTAL_INNER_CENTER => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_inner_middle'],
            LabelPosition::HORIZONTAL_INNER_LEFT => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_inner_left'],
            LabelPosition::HORIZONTAL_OUTER_RIGHT => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_outer_right'],
            LabelPosition::HORIZONTAL_OUTER_CENTER => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_outer_middle'],
            LabelPosition::HORIZONTAL_OUTER_LEFT => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_outer_left']
        ];
    }

    public function loadYLabelPositionOptions(DataContainer $dc) {
        return [
            LabelPosition::VERTICAL_INNER_TOP => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_inner_top'],
            LabelPosition::VERTICAL_INNER_CENTER => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_inner_middle'],
            LabelPosition::VERTICAL_INNER_BOTTOM => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_inner_bottom'],
            LabelPosition::VERTICAL_OUTER_TOP => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_outer_top'],
            LabelPosition::VERTICAL_OUTER_CENTER => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_outer_middle'],
            LabelPosition::VERTICAL_OUTER_BOTTOM => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_outer_bottom']
        ];
    }

    public function loadXValueCharacterOptions(DataContainer $dc) {
        return [
            '1' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_nominal_values'],
            '2' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart']['option_temporal_values'],
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
                    "INSERT INTO tl_c4g_visualization_chart_range (chartId, name, fromX, toX, defaultRange, yMin, yMax, y2Min, y2Max) ".
                    "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute($dc->activeRecord->id, $input['name'], $input['fromX'], $input['toX'], $input['defaultRange'], $input['yMin'], $input['yMax'], $input['y2Min'], $input['y2Max']);
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
            "SELECT name, fromX, toX, defaultRange, yMin, yMax, y2Min, y2Max FROM tl_c4g_visualization_chart_range WHERE chartId = ?");
        $result = $stmt->execute($dc->activeRecord->id)->fetchAllAssoc();
        if ($dc->activeRecord->xValueCharacter === '2') {
            $dateTime = new DateTime();
            $dateFormat = $GLOBALS['TL_CONFIG']['dateFormat'];
            foreach ($result as $key => $value) {
                if ($value['fromX'] === 0.0) {
                    $value['fromX'] = time();
                }
                $dateTime->setTimestamp(intval($value['fromX']));
                $result[$key]['fromX'] = date($dateFormat, $value['fromX']);

                if ($value['toX'] === 0.0) {
                    $value['toX'] = time();
                }
                $dateTime->setTimestamp(intval($value['toX']));
                $result[$key]['toX'] = date($dateFormat, $value['toX']);
            }
        }
        return $result;
    }

    public function changeFileBinToUuid($fieldValue, DataContainer $dc) {
        if ($fieldValue) {
            return \Contao\StringUtil::binToUuid($fieldValue);
        }

        return null;
    }

    public function loadXTickModeOptions(DataContainer $dc): array
    {
        return [
            '' => 'Alle',
            'monthly' => 'Der erste Tag jedes Monats (wenn vorhanden)',
            'yearly' => 'Der erste Tag jedes Jahres (wenn vorhanden)',
            'nth' => 'Benutzerdefiniert (Jedes X. Label anzeigen)',
        ];
    }

    public function loadXLabelText($fieldValue, DataContainer $dc)
    {
        $database = \Contao\Database::getInstance();
        $statement = $database->prepare('SELECT xLabelText FROM tl_c4g_visualization_chart WHERE id = ?');
        $result = $statement->execute($dc->activeRecord->id)->fetchAssoc();
        if ($result !== false) {
            return $result['xLabelText'];
        }
        return '';
    }

    public function loadYLabelText($fieldValue, DataContainer $dc)
    {
        $database = \Contao\Database::getInstance();
        $statement = $database->prepare('SELECT yLabelText FROM tl_c4g_visualization_chart WHERE id = ?');
        $result = $statement->execute($dc->activeRecord->id)->fetchAssoc();
        if ($result !== false) {
            return $result['yLabelText'];
        }
        return '';
    }

    public function loadY2LabelText($fieldValue, DataContainer $dc)
    {
        $database = \Contao\Database::getInstance();
        $statement = $database->prepare('SELECT y2LabelText FROM tl_c4g_visualization_chart WHERE id = ?');
        $result = $statement->execute($dc->activeRecord->id)->fetchAssoc();
        if ($result !== false) {
            return $result['y2LabelText'];
        }
        return '';
    }
}
