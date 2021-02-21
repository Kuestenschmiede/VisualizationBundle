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
use con4gis\CoreBundle\Classes\DCA\Fields\ColorPickerField;
use con4gis\CoreBundle\Classes\DCA\Fields\DatePickerField;
use con4gis\CoreBundle\Classes\DCA\Fields\DigitField;
use con4gis\CoreBundle\Classes\DCA\Fields\IdField;
use con4gis\CoreBundle\Classes\DCA\Fields\LabelField;
use con4gis\CoreBundle\Classes\DCA\Fields\MultiColumnField;
use con4gis\CoreBundle\Classes\DCA\Fields\NaturalField;
use con4gis\CoreBundle\Classes\DCA\Fields\SelectField;
use con4gis\CoreBundle\Classes\DCA\Fields\SQLField;
use con4gis\CoreBundle\Classes\DCA\Fields\TextField;
use con4gis\VisualizationBundle\Classes\Charts\ChartElement;

$dca = new DCA('tl_c4g_visualization_chart_element');
$dca->list()->sorting()->panelLayout('filter;sort,search,limit')
    ->headerFields(['id', 'backendtitle', 'frontendtitle', 'chartTitles']);
$dca->list()->label()->fields(['id', 'backendtitle', 'frontendtitle', 'chartTitles'])
    ->labelCallback('tl_c4g_visualization_chart_element', 'getLabel');
$dca->list()->addRegularOperations($dca);
$dca->palette()->selector(['origin'])
    ->default('{general_legend},backendtitle,frontendtitle,color,redirectSite;'.
        '{type_origin_legend},type,origin;'.
        '{transform_legend},groupIdenticalX,minCountIdenticalX;'.
        '{publish_legend},published;')
    ->subPalette('origin', '1', 'inputWizard')
    ->subPalette('origin', '2', 'table,tablex,tablex2,tabley,whereWizard')
    ->subPalette('origin', '3', 'periodWizard');

$id = new IdField('id', $dca);
$tStamp = new NaturalField('tstamp', $dca);
$backendTitle = new TextField('backendtitle', $dca);
$backendTitle->search()->sorting();
$frontendTitle = new TextField('frontendtitle', $dca);
$frontendTitle->search()->sorting();
$chartTitles = new LabelField('chartTitles', $dca);
$chartTitles->exclude(false);
$color = new ColorPickerField('color', $dca);
$type = new SelectField('type', $dca);
$type->sql("varchar(10) NOT NULL default ''")
    ->optionsCallback('tl_c4g_visualization_chart_element', 'loadTypeOptions')
    ->eval()->submitOnChange();
$origin = new SelectField('origin', $dca);
$origin->sql("char(1) NOT NULL default '2'")
    ->optionsCallback('tl_c4g_visualization_chart_element', 'loadOriginOptions')
    ->default('2')
    ->eval()->submitOnChange();
$inputWizard = new MultiColumnField('inputWizard', $dca);
$inputWizard->saveCallback('tl_c4g_visualization_chart_element', 'saveInput')
    ->loadCallback('tl_c4g_visualization_chart_element', 'loadInput')
    ->eval()->doNotSaveEmpty();
$xInput = new DigitField('xinput', $dca, $inputWizard);
$xInput->eval()->maxlength(21);
$yInput = new DigitField('yinput', $dca, $inputWizard);
$yInput->eval()->maxlength(21);
$table = new SelectField('table', $dca);
$table->optionsCallback('tl_c4g_visualization_chart_element', 'loadTableNames')
    ->sql("varchar(255) NOT NULL default ''")
    ->default('')
    ->eval()->maxlength(255)
    ->doNotSaveEmpty()
    ->submitOnChange()
    ->includeBlankOption();
$tableX = new SelectField('tablex', $dca);
$tableX->optionsCallback('tl_c4g_visualization_chart_element', 'loadColumnNames')
    ->sql("varchar(255) NOT NULL default ''")
    ->default('')
    ->eval()->maxlength(255)
        ->class('w50')
        ->doNotSaveEmpty();
$tableX2 = new SelectField('tablex2', $dca);
$tableX2->optionsCallback('tl_c4g_visualization_chart_element', 'loadColumnNames')
    ->sql("varchar(255) NOT NULL default ''")
    ->default('')
    ->eval()->maxlength(255)
    ->class('w50')
    ->doNotSaveEmpty()
    ->includeBlankOption(true);
$tableY = new SelectField('tabley', $dca);
$tableY->optionsCallback('tl_c4g_visualization_chart_element', 'loadColumnNames')
    ->sql("varchar(255) NOT NULL default ''")
    ->default('')
    ->eval()->maxlength(255)
        ->class('w50')
        ->doNotSaveEmpty();
$whereWizard = new MultiColumnField('whereWizard', $dca);
$whereWizard->saveCallback('tl_c4g_visualization_chart_element', 'saveWhere')
    ->loadCallback('tl_c4g_visualization_chart_element', 'loadWhere')
    ->eval()
    ->doNotSaveEmpty()
    ->class('clr');
$whereColumn = new SelectField('whereColumn', $dca, $whereWizard);
$whereColumn->optionsCallback('tl_c4g_visualization_chart_element', 'loadColumnNames')
    ->default('');
$whereComparison = new SelectField('whereComparison', $dca, $whereWizard);
$whereComparison->optionsCallback('tl_c4g_visualization_chart_element', 'loadComparisonOptions');
$whereValue = new TextField('whereValue', $dca, $whereWizard);
$whereValue->eval()->regEx('alnum');
$groupIdenticalX = new CheckboxField('groupIdenticalX', $dca);
$minCountIdenticalX = new DigitField('minCountIdenticalX', $dca);
$minCountIdenticalX->default('1');
$minCountIdenticalX->sql("int(10) NOT NULL default '1'");

$periodWizard = new MultiColumnField('periodWizard', $dca);
$periodWizard->saveCallback('tl_c4g_visualization_chart_element', 'savePeriod')
    ->loadCallback('tl_c4g_visualization_chart_element', 'loadPeriod');
$fromX = new DatePickerField('fromX', $dca, $periodWizard);
$toX = new DatePickerField('toX', $dca, $periodWizard);
$yinput = new TextField('yinput', $dca, $periodWizard);


$published = new CheckboxField('published', $dca);
$published->default(true);

$importId = new SQLField("importId", $dca, "int(20) unsigned NOT NULL default '0'");
$importId->eval()->doNotCopy(true);

$GLOBALS['TL_DCA']['tl_c4g_visualization_chart_element']['fields']['redirectSite'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['redirectSite'],
    'exclude'                 => true,
    'inputType'               => 'pageTree',
    'foreignKey'              => 'tl_page.title',
    'eval'                    => array('mandatory'=>false, 'fieldType'=>'radio', 'tl_class'=>'long clr'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
    'relation'                => array('type'=>'hasOne', 'load'=>'eager')
];

/**
 * Class tl_c4g_visualization_chart_element
 */
class tl_c4g_visualization_chart_element extends \Backend
{
    protected $columns = [];

    public function getLabel($row) {
        $labels = [];
        $labels['id'] = $row['id'];
        $labels['backendtitle'] = $row['backendtitle'];
        $labels['frontendtitle'] = $row['frontendtitle'];
        $relations = \con4gis\VisualizationBundle\Resources\contao\models\ChartElementRelationModel::findByElementId($row['id']);
        $chartTitles = [];
        foreach ($relations as $relation) {
            $chart = \con4gis\VisualizationBundle\Resources\contao\models\ChartModel::findByPk($relation->chartId);
            $chartTitles[] = $chart->backendtitle;
        }
        $labels['chartTitles'] = implode(', ', array_unique($chartTitles));
        return $labels;
    }

    public function loadOriginOptions(DataContainer $dc)
    {
        return [
            '1' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_input'],
            '2' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_load_from_table'],
            '3' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_period'],
        ];
    }

    public function loadTypeOptions(DataContainer $dc)
    {
        return [
            ChartElement::TYPE_LINE => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_line'],
            ChartElement::TYPE_AREA_LINE => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_area_line'],
            ChartElement::TYPE_SPLINE => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_spline'],
            ChartElement::TYPE_AREA_SPLINE => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_area_spline'],
            ChartElement::TYPE_PIE => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_pie'],
            ChartElement::TYPE_BAR => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_bar'],
            ChartElement::TYPE_DONUT => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_donut'],
            ChartElement::TYPE_GAUGE => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_gauge'],
            ChartElement::TYPE_GANTT => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_gantt']
        ];
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
                if ($y !== 0.0) {
                    if ($x === 0.0) {
                        $x = 1.0;
                    }
                    $stmt = $database->prepare(
                        "INSERT INTO tl_c4g_visualization_chart_element_input (elementId, x, y) ".
                        "VALUES (?, ?, ?)");
                    $stmt->execute($dc->activeRecord->id, $x, $y);
                }
            }
        }
        return null;
    }

    /**
     * @param $value
     * @param DataContainer $dc
     * @return array
     */
    public function loadWhere($value, DataContainer $dc) : array {
        $database = \Contao\Database::getInstance();
        $stmt = $database->prepare(
            "SELECT whereColumn, whereComparison, whereValue FROM tl_c4g_visualization_chart_element_condition WHERE elementId = ?");
        $result = $stmt->execute($dc->activeRecord->id);
        return $result->fetchAllAssoc();
    }

    /**
     * @param $value
     * @param DataContainer $dc
     * @return null
     */
    public function saveWhere($value, DataContainer $dc) {
        $conditions = unserialize($value);
        if (is_array($conditions) === true) {
            $database = \Contao\Database::getInstance();
            $database->prepare(
                "DELETE FROM tl_c4g_visualization_chart_element_condition WHERE elementId = ?")->execute($dc->activeRecord->id);
            foreach($conditions as $condition) {
                if ($condition['whereColumn'] !== '' && $condition['whereComparison'] !== '' && $condition['whereValue'] !== '') {
                    $stmt = $database->prepare(
                        "INSERT INTO tl_c4g_visualization_chart_element_condition (elementId, whereColumn, whereComparison, whereValue) ".
                        "VALUES (?, ?, ?, ?)");
                    $stmt->execute($dc->activeRecord->id, $condition['whereColumn'], $condition['whereComparison'], $condition['whereValue']);
                }
            }
        }
        return null;
    }

    public function loadTableNames(DataContainer $dc) {
        $db = Database::getInstance();
        $tables = $db->listTables();
        $tablesFormatted = [];
        foreach ($tables as $table) {
            $tablesFormatted[$table] = $table;
        }
        return $tablesFormatted;
    }

    public function loadColumnNames($dc) {
        if ($this->columns === []) {
            $db = Database::getInstance();
            if ($dc->activeRecord->table !== '') {
                $columns = $db->listFields($dc->activeRecord->table);
                if (is_array($columns) === true) {
                    $columnsFormatted = [];
                    foreach ($columns as $column) {
                        if ($column['name'] !== 'PRIMARY') {
                            $columnsFormatted[$column['name']] = $column['name'];
                        }
                    }
                    $this->columns = $columnsFormatted;
                    return $columnsFormatted;
                }
            }
        } else {
            return $this->columns;
        }
        return [];
    }

    public function loadComparisonOptions($dc) {
        return [
            '1' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_equal'],
            '2' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_greater_or_equal'],
            '3' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_lesser_or_equal'],
            '4' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_not_equal'],
            '5' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_greater'],
            '6' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_lesser']
        ];
    }

    /**
     * @param $value
     * @param DataContainer $dc
     * @return null
     */
    public function savePeriod($value, DataContainer $dc)  {
        $inputs = unserialize($value);
        if (is_array($inputs) === true) {
            $database = \Contao\Database::getInstance();
            $database->prepare(
                "DELETE FROM tl_c4g_visualization_chart_element_period WHERE elementId = ?")->execute($dc->activeRecord->id);
            foreach($inputs as $input) {
                if (empty($input) === true || $input['yinput'] === '' || $input['fromX'] === '' || $input['toX'] === '') {
                    continue;
                }

                $stmt = $database->prepare(
                    "INSERT INTO tl_c4g_visualization_chart_element_period (elementId, fromX, toX, yinput) ".
                    "VALUES (?, ?, ?, ?)");
                $stmt->execute($dc->activeRecord->id, $input['fromX'], $input['toX'], $input['yinput']);
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
    public function loadPeriod($value, DataContainer $dc) : array {
        $database = \Contao\Database::getInstance();
        $stmt = $database->prepare(
            "SELECT fromX, toX, yinput FROM tl_c4g_visualization_chart_element_period WHERE elementId = ?");
        $result = $stmt->execute($dc->activeRecord->id)->fetchAllAssoc();

        //if ($dc->activeRecord->xValueCharacter === '2') {
            $dateTime = new DateTime();
            $dateFormat = $GLOBALS['TL_CONFIG']['dateFormat'];
            foreach ($result as $key => $value) {
                if ($value['fromX'] === 0.0) {
                    $value['fromX'] = time();
                }
                $dateTime->setTimestamp(intval($value['fromX']));
                $year = $dateTime->format('Y');
                $month = $dateTime->format('m');
                $day = $dateTime->format('d');


                $result[$key]['fromX'] = date($dateFormat, $value['fromX']);//"$month/$day/$year";

                if ($value['toX'] === 0.0) {
                    $value['toX'] = time();
                }
                $dateTime->setTimestamp(intval($value['toX']));
                $year = $dateTime->format('Y');
                $month = $dateTime->format('m');
                $day = $dateTime->format('d');
                $result[$key]['toX'] = date($dateFormat, $value['toX']);//"$month/$day/$year";
            }
        //}
        return $result;
    }
}
