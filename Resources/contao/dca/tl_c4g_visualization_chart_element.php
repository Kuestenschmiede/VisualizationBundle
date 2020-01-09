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
use con4gis\CoreBundle\Classes\DCA\Fields\DigitField;
use con4gis\CoreBundle\Classes\DCA\Fields\IdField;
use con4gis\CoreBundle\Classes\DCA\Fields\LabelField;
use con4gis\CoreBundle\Classes\DCA\Fields\MultiColumnField;
use con4gis\CoreBundle\Classes\DCA\Fields\NaturalField;
use con4gis\CoreBundle\Classes\DCA\Fields\SelectField;
use con4gis\CoreBundle\Classes\DCA\Fields\TextField;
use con4gis\VisualizationBundle\Classes\Charts\ChartElement;

$dca = new DCA('tl_c4g_visualization_chart_element');
$dca->list()->sorting()->panelLayout('filter;sort,search,limit')
    ->headerFields(['id', 'backendtitle', 'frontendtitle', 'chartTitles']);
$dca->list()->label()->fields(['id', 'backendtitle', 'frontendtitle', 'chartTitles'])
    ->labelCallback('tl_c4g_visualization_chart_element', 'getLabel');
$dca->list()->addRegularOperations($dca);
$dca->palette()->selector(['origin'])
    ->default('{general_legend},backendtitle,frontendtitle,color;'.
        '{type_origin_legend},type,origin;'.
        '{transform_legend},groupIdenticalX;'.
        '{publish_legend},published;')
    ->subPalette('origin', '1', 'inputWizard')
    ->subPalette('origin', '2', 'table,tablex,tabley,whereWizard');

$id = new IdField('id', $dca);
$published = new CheckboxField('published', $dca);
$published->default(true);
$tStamp = new NaturalField('tstamp', $dca);
$frontendTitle = new TextField('frontendtitle', $dca);
$frontendTitle->search()->sorting();
$backendTitle = new TextField('backendtitle', $dca);
$backendTitle->search()->sorting();
$chartTitles = new LabelField('chartTitles', $dca);
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
            '2' => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_load_from_table']
        ];
    }

    public function loadTypeOptions(DataContainer $dc)
    {
        return [
            ChartElement::TYPE_LINE => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_line'],
            ChartElement::TYPE_PIE => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_pie'],
            ChartElement::TYPE_BAR => $GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_bar']
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
    

}
