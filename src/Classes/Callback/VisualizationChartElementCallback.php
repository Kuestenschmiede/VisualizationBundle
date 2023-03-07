<?php

namespace con4gis\VisualizationBundle\Classes\Callback;

use con4gis\VisualizationBundle\Classes\Charts\ChartElement;
use con4gis\VisualizationBundle\Resources\contao\models\ChartElementRelationModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;
use Contao\Backend;
use Contao\Config;
use Contao\Database;
use Contao\DataContainer;
use Contao\StringUtil;
use MenAtWork\MultiColumnWizardBundle\Contao\Widgets\MultiColumnWizard;
use Safe\DateTimeImmutable;

class VisualizationChartElementCallback extends Backend
{
    protected $columns = [];
    
    public function getLabel($row) {
        $labels = [];
        $labels['id'] = $row['id'];
        $labels['backendtitle'] = $row['backendtitle'];
        $labels['frontendtitle'] = $row['frontendtitle'];
        $relations = ChartElementRelationModel::findByElementId($row['id']);
        $chartTitles = [];
        if ($relations !== null) {
            foreach ($relations as $relation) {
                $chart = ChartModel::findByPk($relation->chartId);
                $chartTitles[] = $chart->backendtitle;
            }
        }
        $labels['chartTitles'] = implode(', ', array_unique($chartTitles));
        return $labels;
    }
    
    public function loadOriginOptions(DataContainer $dc)
    {
        return [
            '1' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_input'],
            '2' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_load_from_table'],
            '3' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_period'],
        ];
    }
    
    public function loadTypeOptions(DataContainer $dc)
    {
        return [
            '' => '-',
            ChartElement::TYPE_LINE => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_line'],
            ChartElement::TYPE_AREA_LINE => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_area_line'],
            ChartElement::TYPE_SPLINE => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_spline'],
            ChartElement::TYPE_AREA_SPLINE => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_area_spline'],
            ChartElement::TYPE_PIE => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_pie'],
            ChartElement::TYPE_BAR => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_bar'],
            ChartElement::TYPE_DONUT => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_donut'],
            ChartElement::TYPE_GAUGE => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_gauge'],
            ChartElement::TYPE_GANTT => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_gantt']
        ];
    }
    
    /**
     * @param $value
     * @param DataContainer $dc
     * @return array
     */
    public function loadInput($value, DataContainer $dc) : array {
        $database = Database::getInstance();
        $stmt = $database->prepare(
            "SELECT x as xinput, y as yinput FROM tl_c4g_visualization_chart_element_input WHERE elementId = ?");
        $result = $stmt->execute($dc->activeRecord->id);
        
        $activeRecord = $dc->activeRecord;
        
        $arrResult = $result->fetchAllAssoc();
        $format = Config::get("datimFormat");
        
        if ($activeRecord->xValueCharacter === '2') {
            foreach ($arrResult as $key => $item) {
                $arrResult[$key]['xinput'] = date($format, $item['xinput']);
            }
        }
        
        return $arrResult;
    }
    
    /**
     * @param $value
     * @param DataContainer $dc
     * @return null
     */
    public function saveInput($value, DataContainer $dc) {
        $inputs = unserialize($value);
        
        $activeRecord = $dc->activeRecord;
        
        if (is_array($inputs) === true) {
            $database = Database::getInstance();
            $database->prepare(
                "DELETE FROM tl_c4g_visualization_chart_element_input WHERE elementId = ?")->execute($dc->activeRecord->id);
            foreach($inputs as $input) {
                $x = floatval($input['xinput']);
                if ($activeRecord->xValueCharacter !== "2") {
                    if ($x === 0.0) {
                        $x = 1.0;
                    }
                } else {
                    $format = Config::get("datimFormat");
                    if ($x == $input['xinput']) {
                        // input was number
                        $dateTime = new DateTimeImmutable();
                        $dateTime = $dateTime->setTimestamp($input['xinput']);
                    } else {
                        $dateTime = new DateTimeImmutable($input['xinput']);
                    }
                    
                    if ($dateTime) {
                        if (strlen($input['xinput']) < 11) {
                            // string only contains date, so set time to 0
                            $dateTime = $dateTime->setTime(0, 0, 0);
                        }
                        
                        $x = $dateTime->getTimestamp();
                    }
                }
                
                $y = floatval($input['yinput']);
                
                if ($y !== 0.0) {
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
        $database = Database::getInstance();
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
            $database = Database::getInstance();
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
        
        if ($dc instanceof MultiColumnWizard) {
            $dca = $dc->dataContainer;
            $id = $dca->id;
            
            $activeRecord = Database::getInstance()->prepare("SELECT * FROM tl_c4g_visualization_chart_element WHERE `id` = ?")
                ->execute($id)->fetchAssoc();
        }
    
        if (!$activeRecord) {
            $activeRecord = $dc->activeRecord;
        }

        if ($this->columns === []) {
            $db = Database::getInstance();
            if (is_array($activeRecord)) {
                $tableName = $activeRecord['table'];
            } else {
                $tableName = $activeRecord->table;
            }
            
            if ($tableName !== '' && $tableName !== null) {
                $columns = $db->listFields($tableName);
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
            '1' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_equal'],
            '2' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_greater_or_equal'],
            '3' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_lesser_or_equal'],
            '4' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_not_equal'],
            '5' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_greater'],
            '6' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_lesser']
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
            $database = Database::getInstance();
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
        $database = Database::getInstance();
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
    
    public function loadXValueCharacterOptions(DataContainer $dc) {
        return [
            '1' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_nominal_values'],
            '2' => &$GLOBALS['TL_LANG']['tl_c4g_visualization_chart_element']['option_temporal_values'],
        ];
    }
}