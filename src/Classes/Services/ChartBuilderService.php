<?php

namespace con4gis\VisualizationBundle\Classes\Services;

use con4gis\VisualizationBundle\Classes\Charts\Axis;
use con4gis\VisualizationBundle\Classes\Charts\Chart;
use con4gis\VisualizationBundle\Classes\Charts\ChartElement;
use con4gis\VisualizationBundle\Classes\Charts\CoordinateSystem;
use con4gis\VisualizationBundle\Classes\Charts\Tooltip;
use con4gis\VisualizationBundle\Classes\Exceptions\EmptyChartException;
use con4gis\VisualizationBundle\Classes\Exceptions\UnknownChartException;
use con4gis\VisualizationBundle\Classes\Exceptions\UnknownChartSourceException;
use con4gis\VisualizationBundle\Classes\Source\Source;
use con4gis\VisualizationBundle\Classes\Transformers\ExtractYearFromXTimestampTransformer;
use con4gis\VisualizationBundle\Classes\Transformers\GroupIdenticalXTransformer;
use con4gis\VisualizationBundle\Resources\contao\models\ChartElementConditionModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartElementModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartElementPeriodModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartRangeModel;
use Contao\Database;
use Contao\Model\Collection;
use Contao\System;
use Psr\Log\LoggerInterface;

class ChartBuilderService
{
    /**
     * @var Database
     */
    private $database;
    
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public function createChartFromId($chartId)
    {
        $chartModel = ChartModel::findByPk($chartId);
        if ($chartModel instanceof ChartModel === true && $chartModel->published === '1') {
            $chart = new Chart();
            $coordinateSystem = new CoordinateSystem(new Axis(), new Axis(), new Axis());
            
            $chart = $this->addConfigToChart($chart, $chartModel, $coordinateSystem);
        
            $chart = $this->addRangesToChart($chart, $chartId);
        
            $chart = $this->addElementsToChart($chart, $chartId, $chartModel, $coordinateSystem);
        } else {
            throw new UnknownChartException();
        }
        
        return $chart;
    }
    
    private function addConfigToChart(Chart $chart, ChartModel $chartModel, CoordinateSystem $coordinateSystem)
    {
        $chart->setZoom($chartModel->zoom);
        $chart->setPoints($chartModel->points);
        $chart->setLegend($chartModel->legend);
        $chart->setTooltips($chartModel->tooltips);
        $chart->setLabels($chartModel->labels);
        $chart->setOneLabelPerElement($chartModel->oneLabelPerElement);
        $chart->setShowSubchart($chartModel->showSubchart);
        $chart->setSubchartHeight($chartModel->subchartHeight);
        $chart->setSubchartShowXAxis($chartModel->subchartShowXAxis);
        
        $tooltip = new Tooltip();
        $chart->setTooltip($tooltip);
        if ($chartModel->swapAxes === '1') {
            $coordinateSystem->setRotated(true);
        }
        $chart->setCoordinateSystem($coordinateSystem);
        if ($chartModel->xshow === '1') {
            $coordinateSystem->x()->setShow(true);
            if (is_string($chartModel->xLabelText) === true) {
                $coordinateSystem->x()->setLabel($chartModel->xLabelText, $chartModel->xLabelPosition);
            }
        }
        
        if ($chartModel->gridX === '1') {
            $chart->setGridX(true);
        }
        if ($chartModel->gridY === '1') {
            $chart->setGridY(true);
        }
    
        if ($chartModel->yshow === '1') {
            $coordinateSystem->y()->setShow(true);
            if ($chartModel->yInverted === '1') {
                $coordinateSystem->y()->setInverted(true);
            }
            if (is_string($chartModel->yLabelText) === true) {
                $coordinateSystem->y()->setLabel($chartModel->yLabelText, $chartModel->yLabelPosition);
            }
            if ($chartModel->yFormat) {
                $coordinateSystem->y()->setTickFormat($chartModel->yFormat);
            }
    
            if ($chartModel->yLabelCount) {
                $coordinateSystem->y()->setLabelCount(intval($chartModel->yLabelCount));
            }

            if ($chartModel->yMin !== null) {
                $coordinateSystem->y()->setMin((int) $chartModel->yMin);
            }

            if ($chartModel->yMax !== null) {
                $coordinateSystem->y()->setMax((int) $chartModel->yMax);
            }
        }
    
        if ($chartModel->y2show === '1') {
            $coordinateSystem->y2()->setShow(true);
            if ($chartModel->yInverted === '1') {
                $coordinateSystem->y2()->setInverted(true);
            }
            if (is_string($chartModel->y2Labeltext) === true) {
                $coordinateSystem->y2()->setLabel($chartModel->y2Labeltext, $chartModel->y2LabelPosition);
            }
            if ($chartModel->y2Format) {
                $coordinateSystem->y2()->setTickFormat($chartModel->y2Format);
            }
    
            if ($chartModel->y2LabelCount) {
                $coordinateSystem->y2()->setLabelCount(intval($chartModel->y2LabelCount));
            }

            if ($chartModel->y2Min !== null) {
                $coordinateSystem->y2()->setMin((int) $chartModel->y2Min);
            }

            if ($chartModel->y2Max != null) {
                $coordinateSystem->y2()->setMax((int) $chartModel->y2Max);
            }
        }
        
        return $chart;
    }
    
    private function addRangesToChart(Chart $chart, $chartId)
    {
        $rangeModels = ChartRangeModel::findByChartId($chartId);
        if ($rangeModels) {
            foreach ($rangeModels as $model) {
                $chart->addRange($model->name, $model->fromX, $model->toX, $model->defaultRange === '1', $model->yMin, $model->yMax, $model->y2Min, $model->y2Max);
            }
        }
        
        return $chart;
    }
    
    private function addElementsToChart(Chart $chart, $chartId, ChartModel $chartModel, CoordinateSystem $coordinateSystem)
    {
        $elementModels = ChartElementModel::findByChartId($chartId);
        if ($chart === null || $elementModels === null) {
            throw new EmptyChartException();
        }

        $db = Database::getInstance();
        foreach ($elementModels as $elementModel) {
            if ($elementModel->published === '1') {
                switch ($elementModel->origin) {
                    case ChartElement::ORIGIN_INPUT:
                        $sql = "SELECT * FROM tl_c4g_visualization_chart_element_input WHERE `elementId` = ?";
                        $elementInputs = $db->prepare($sql)->execute($elementModel->id)->fetchAllAssoc();
                        if ($elementInputs !== null) {
                            $source = new Source($elementInputs, $elementModel->minCountIdenticalX, $elementModel->redirectSite);
                        }
                        break;
                    case ChartElement::ORIGIN_TABLE:
                        try {
                            $table = $elementModel->table;
                            $query = "SELECT * FROM " . $table;
                            $additionalWhereString = $this->createAdditionalWhereString($elementModel);
                            if ($additionalWhereString !== '') {
                                $query .= " WHERE " . $additionalWhereString;
                            }
                            if ($elementModel->tablex) {
                                $query .= " ORDER BY " . $elementModel->tablex;
                            }
                            $stmt = $db->prepare($query);
                            $result = $stmt->execute();
                            $arrResult = $result->fetchAllAssoc();
                            if (!$arrResult || count($arrResult) <= 0) {
                                continue 2;
                            }
                            $source = new Source($arrResult, $elementModel->minCountIdenticalX, $elementModel->redirectSite);
                        } catch (\Throwable $throwable) {
                            $this->log($throwable);
                            continue 2;
                        }
                        break;
                    case ChartElement::ORIGIN_PERIOD:
                        $periodModels = ChartElementPeriodModel::findByElementId($elementModel->id);
                        if ($periodModels !== null) {
                            foreach ($periodModels as $period) {
                                $period->x = $period->fromX;
                                $period->x2 = $period->toX;
                                $period->y = $period->yinput;
                            }
                            $source = new Source($periodModels, $elementModel->minCountIdenticalX, $elementModel->redirectSite);
                        }
                        break;
                    default:
                        throw new UnknownChartSourceException();
                        break;
                }
            
                $element = new ChartElement($elementModel->type, $source);
                $element->setDecimalPoints($chartModel->decimalPoints);
                $element->setShowEmptyYValues($chartModel->showEmptyYValues);
                $element->setYAxisSelection($elementModel->yAxisSelection);
                $element->setTooltipExtension($elementModel->tooltipExtension ?: "");
                if ($elementModel->color) {
                    $element->setColor($elementModel->color);
                }
                if ($elementModel->frontendtitle) {
                    $element->setName($elementModel->frontendtitle);
                }
                if ($elementModel->origin === ChartElement::ORIGIN_TABLE) {
                    $element->setX($elementModel->tablex)->setY($elementModel->tabley);
                
                    if ($elementModel->type === ChartElement::TYPE_GANTT) {
                        $element->setX2($elementModel->tablex2);
                    }
                }
                if ($elementModel->origin === ChartElement::ORIGIN_PERIOD) {
                    $periods = ChartElementPeriodModel::findByElementId($elementModel->id);
                    $x = 0;
                    $x2 = 0;
                    $y = 0;
                    foreach ($periods as $period) {
                        if ($x == 0) {
                            $x = $period->fromX;
                        } else if ($period->fromX < $x) {
                            $x = $period->fromX;
                        }
                    
                        if ($x2 == 0) {
                            $x2 = $period->toX;
                        } else if ($period->toX > $x2) {
                            $x2 = $period->toX;
                        }
                    
                        $y = $period->yinput; //ToDo
                    }
                
                    $element->setX($x);
                    $element->setX2($x2);
                }
                
                if ($chartModel->xValueCharacter === '2') {
                    $element->mapTimeValues(
                        $chartModel,
                        $coordinateSystem,
                        $chart->getTooltip()
                    );
                }
                if ($elementModel->groupIdenticalX === '1') {
                    $element->addTransformer(new GroupIdenticalXTransformer());
                }
                if ($elementModel->extractYearFromXTimestamp === '1') {
                    $element->addTransformer(new ExtractYearFromXTimestampTransformer());
                }
            
                $chart->addElement($element);
            }
        }
        
        return $chart;
    }
    
    private function createAdditionalWhereString($elementModel) {
        $conditionModels = ChartElementConditionModel::findByElementId($elementModel->id);
        if ($conditionModels instanceof Collection) {
            $first = true;
            $where = '';
            foreach($conditionModels as $model) {
                if ($first === true) {
                    $first = false;
                } else {
                    $where .= ' AND ';
                }
                switch ($model->whereComparison) {
                    case 1:
                        $comparison = '=';
                        break;
                    case 2:
                        $comparison = '>=';
                        break;
                    case 3:
                        $comparison = '<=';
                        break;
                    case 4:
                        $comparison = '!=';
                        break;
                    case 5:
                        $comparison = '>';
                        break;
                    case 6:
                        $comparison = '<';
                        break;
                    default:
                        return '';
                }
                $where .= $model->whereColumn . ' ' . $comparison . ' \'' . $model->whereValue . '\'';
            }
            return $where;
        } else {
            return '';
        }
    }
    
    private function log(\Throwable $throwable) {
        $this->logger->error($throwable->getMessage() . "\n" . $throwable->getTraceAsString());
    }
}