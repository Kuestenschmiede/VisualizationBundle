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
use con4gis\CoreBundle\Classes\DCA\Fields\CheckboxField;
use con4gis\CoreBundle\Classes\DCA\Fields\DatePickerField;
use con4gis\CoreBundle\Classes\DCA\Fields\DigitField;
use con4gis\CoreBundle\Classes\DCA\Fields\IdField;
use con4gis\CoreBundle\Classes\DCA\Fields\ImageField;
use con4gis\CoreBundle\Classes\DCA\Fields\MultiColumnField;
use con4gis\CoreBundle\Classes\DCA\Fields\NaturalField;
use con4gis\CoreBundle\Classes\DCA\Fields\SelectField;
use con4gis\CoreBundle\Classes\DCA\Fields\SQLField;
use con4gis\CoreBundle\Classes\DCA\Fields\TextField;

$palettes = [
    'general' => '{general_legend},backendtitle,xValueCharacter,',
    'elements' => 'elementWizard',
    'ranges_nominal' => ';{ranges_legend},rangeWizardNominal,buttonAllCaption,buttonPosition,buttonAllPosition,loadOutOfRangeData',
    'ranges_time' => ';{ranges_legend},rangeWizardTime,buttonAllCaption,buttonPosition,buttonAllPosition,loadOutOfRangeData',
    'coordinate_system_nominal' => ';{coordinate_system_legend},swapAxes,xshow,xLabelText,xRotate,xLabelCount,yshow,yInverted,yLabelText,yLabelPosition', //,y2show,y2Inverted,y2LabelText,y2LabelPosition
    'coordinate_system_time' => ';{coordinate_system_legend},swapAxes,xshow,xLabelText,xLabelPosition,xRotate,xTimeFormat,xLabelCount,yshow,yInverted,yLabelText,yLabelPosition', //,y2show,y2Inverted,y2LabelText,y2LabelPosition
    'watermark' => ';{watermark_legend:hide},image,imageMaxHeight,imageMaxWidth,imageMarginTop,imageMarginLeft,imageOpacity',
    'expert' => ';{expert_legend:hide},zoom',
    'publish' => ';{publish_legend},published'
];

/**
 * Table tl_c4g_visualization_chart
 */
$dca = new DCA('tl_c4g_visualization_chart');

$dca->list()->sorting()->headerFields(['id', 'backendtitle']);
$dca->list()->label()->fields(['id', 'backendtitle']);
$dca->list()->addRegularOperations($dca);

$dca->palette()->selector(['xValueCharacter']);
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

$rangeWizardTime = new MultiColumnField('rangeWizardTime', $dca);
$rangeWizardTime->saveCallback('tl_c4g_visualization_chart', 'saveRanges')
    ->loadCallback('tl_c4g_visualization_chart', 'loadRanges');
$name = new TextField('name', $dca, $rangeWizardTime);
$fromX = new DatePickerField('fromX', $dca, $rangeWizardTime);
$toX = new DatePickerField('toX', $dca, $rangeWizardTime);
$defaultRange = new CheckboxField('defaultRange', $dca, $rangeWizardTime);

$buttonAllCaption = new TextField('buttonAllCaption', $dca);
$buttonPosition = new SelectField('buttonPosition', $dca);
$buttonPosition->optionsCallback('tl_c4g_visualization_chart', 'loadButtonPositionOptions')
    ->eval()->class('w50');
$buttonAllPosition = new SelectField('buttonAllPosition', $dca);
$buttonAllPosition->optionsCallback('tl_c4g_visualization_chart', 'loadButtonAllPositionOptions')
    ->default('2')->sql("char(1) NOT NULL default '2'")->eval()->class('w50');
$loadOutOfRangeData = new CheckboxField('loadOutOfRangeData', $dca);

$swapAxes = new CheckboxField('swapAxes', $dca);
$xShow = new CheckboxField('xshow', $dca);
$xShow->default(true);
$xLabelText = new TextField('xLabelText', $dca);
$xTimeFormat = new TextField('xTimeFormat', $dca);
$xTimeFormat->default('d.m.Y')->sql("varchar(255) NOT NULL default 'd.m.Y'")->eval()->class('clr');
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
$xLabelPosition->optionsCallback('tl_c4g_visualization_chart', 'loadLabelPositionOptions');
$xLabelPosition->eval()->class('w50');

$yShow = new CheckboxField('yshow', $dca);
$yShow->default(true);
$yInverted = new CheckboxField('yInverted', $dca);
$yLabelText = new TextField('yLabelText', $dca);
$yLabelText->eval()->class('w50');
$yLabelPosition = new SelectField('yLabelPosition', $dca);
$yLabelPosition->optionsCallback('tl_c4g_visualization_chart', 'loadLabelPositionOptions');
$yLabelPosition->eval()->class('w50');

$y2Show = new CheckboxField('y2show', $dca);
$y2Show->default(false);
$y2Inverted = new CheckboxField('y2Inverted', $dca);
$y2LabelText = new TextField('y2LabelText', $dca);
$y2LabelText->eval()->class('w50');
$y2LabelPosition = new SelectField('y2LabelPosition', $dca);
$y2LabelPosition->optionsCallback('tl_c4g_visualization_chart', 'loadLabelPositionOptions');
$y2LabelPosition->eval()->class('w50');

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

$published = new CheckboxField('published', $dca);
$published->default(true);

$importId = new SQLField("importId", $dca, "int(20) unsigned NOT NULL default '0'");
$importId->eval()->doNotCopy(true);

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
