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
namespace con4gis\VisualizationBundle\Controller;

use con4gis\CoreBundle\Resources\contao\models\C4gLogModel;
use con4gis\VisualizationBundle\Classes\Charts\Axis;
use con4gis\VisualizationBundle\Classes\Charts\Chart;
use con4gis\VisualizationBundle\Classes\Charts\ChartElement;
use con4gis\VisualizationBundle\Classes\Charts\CoordinateSystem;
use con4gis\VisualizationBundle\Classes\Charts\Tooltip;
use con4gis\VisualizationBundle\Classes\Exceptions\EmptyChartException;
use con4gis\VisualizationBundle\Classes\Exceptions\UnknownChartException;
use con4gis\VisualizationBundle\Classes\Exceptions\UnknownChartSourceException;
use con4gis\VisualizationBundle\Classes\Source\Source;
use con4gis\VisualizationBundle\Classes\Transformers\AddIdenticalYTransformer;
use con4gis\VisualizationBundle\Classes\Transformers\GroupIdenticalXTransformer;
use con4gis\VisualizationBundle\Resources\contao\models\ChartElementConditionModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartElementInputModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartElementModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartRangeModel;
use Contao\Database;
use Contao\Model\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChartController extends AbstractController
{
    public function fetchAction(Request $request, $chartId)
    {
        try {
            $this->get('contao.framework')->initialize();
            if ($chartId && $this->authorized() === true) {
                $chartModel = ChartModel::findByPk($chartId);
                if ($chartModel instanceof ChartModel === true && $chartModel->published === '1') {
                    $chart = new Chart();
                    $chart->setZoom($chartModel->zoom);
                    $chart->setPoints($chartModel->points);
                    $chart->setLegend($chartModel->legend);
                    $chart->setTooltips($chartModel->tooltips);
                    $chart->setLabels($chartModel->labels);
                    $chart->setOneLabelPerElement($chartModel->oneLabelPerElement);
                    $coordinateSystem = new CoordinateSystem(new Axis, new Axis, new Axis);
                    $tooltip = new Tooltip();
                    $chart->setTooltip($tooltip);
                    if ($chartModel->swapAxes === '1') {
                        $coordinateSystem->setRotated(true);
                    }
                    $chart->setCoordinateSystem($coordinateSystem);
                    if ($chartModel->xshow === '1') {
                        $coordinateSystem->x()->setShow(true);
                        if (is_string($chartModel->xLabelText) === true) {
                            $coordinateSystem->x()->setLabel($chartModel->xLabelText, intval($chartModel->xLabelPosition));
                        }
                    }

                    if ($chartModel->yshow === '1') {
                        $coordinateSystem->y()->setShow(true);
                        if ($chartModel->yInverted === '1') {
                            $coordinateSystem->y()->setInverted(true);
                        }
                        if (is_string($chartModel->yLabelText) === true) {
                            $coordinateSystem->y()->setLabel($chartModel->yLabelText, intval($chartModel->yLabelPosition));
                        }
                    }

                    if ($chartModel->y2show === '1') {
                        $coordinateSystem->y2()->setShow(true);
                        if ($chartModel->yInverted === '1') {
                            $coordinateSystem->y2()->setInverted(true);
                        }
                        if (is_string($chartModel->y2LabelText) === true) {
                            $coordinateSystem->y2()->setLabel($chartModel->y2LabelText, intval($chartModel->y2LabelPosition));
                        }
                    }

                    $rangeModels = ChartRangeModel::findByChartId($chartId);
                    foreach ($rangeModels as $model) {
                        $chart->addRange($model->name, $model->fromX, $model->toX, $model->defaultRange === '1');
                    }

                    $elementModels = ChartElementModel::findByChartId($chartId);
                    if ($elementModels === null) {
                        throw new EmptyChartException();
                    }

                    foreach ($elementModels as $elementModel) {
                        if ($elementModel->published === '1') {
                            switch ($elementModel->origin) {
                                case ChartElement::ORIGIN_INPUT:
                                    $inputModels = ChartElementInputModel::findByElementId($elementModel->id);
                                    if ($inputModels !== null) {
                                        $source = new Source($inputModels, $elementModel->minCountIdenticalX, $elementModel->redirectSite);
                                    }
                                    break;
                                case ChartElement::ORIGIN_TABLE:
                                    try {
                                        $table = $elementModel->table;
                                        $x = $elementModel->tablex;
                                        $x2 = $elementModel->tablex2;
                                        $database = Database::getInstance();
                                        if ($rangeModels instanceof Collection && $chartModel->loadOutOfRangeData !== '1') {
                                            $fromX = 0;
                                            $toX = 0;
                                            foreach ($rangeModels as $model) {
                                                if ($fromX === 0 || $fromX > $model->fromX) {
                                                    $fromX = $model->fromX;
                                                }

                                                if ($toX === 0 || $toX < $model->toX) {
                                                    $toX = $model->toX;
                                                }
                                            }
                                            $query = "SELECT * FROM $table WHERE $x >= ? AND $x <= ?";
                                            $additionalWhereString = $this->createAdditionalWhereString($elementModel);
                                            if ($additionalWhereString !== '') {
                                                $query .= " AND " . $additionalWhereString;
                                            }
                                            $stmt = $database->prepare($query);
                                            $result = $stmt->execute($fromX, $toX);
                                            $arrResult = $result->fetchAllAssoc();
                                            if (!$arrResult || count($arrResult) <= 0) {
                                                continue 2;
                                            }
                                            $source = new Source($arrResult, $elementModel->minCountIdenticalX, $elementModel->redirectSite);
                                        } else {
                                            $query = "SELECT * FROM " . $table;
                                            $additionalWhereString = $this->createAdditionalWhereString($elementModel);
                                            if ($additionalWhereString !== '') {
                                                $query .= " WHERE " . $additionalWhereString;
                                            }
                                            $stmt = $database->prepare($query);
                                            $result = $stmt->execute();
                                            $arrResult = $result->fetchAllAssoc();
                                            if (!$arrResult || count($arrResult) <= 0) {
                                                continue 2;
                                            }
                                            $source = new Source($arrResult, $elementModel->minCountIdenticalX, $elementModel->redirectSite);
                                        }
                                    } catch (\Throwable $throwable) {
                                        $this->log($throwable);
                                        continue 2;
                                    }
                                    break;
                                default:
                                    throw new UnknownChartSourceException();
                                    break;
                            }

                            $element = new ChartElement($elementModel->type, $source);
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
                            if ($chartModel->xValueCharacter === '2') {
                                $element->mapTimeValues($chartModel->xTimeFormat, $coordinateSystem, $tooltip, $chartModel->xLabelCount, intval($chartModel->xRotate));
                            }
                            if ($elementModel->groupIdenticalX === '1') {
                                $element->addTransformer(new GroupIdenticalXTransformer());
                            }

                            if ($elementModel->redirectSite && (($jumpTo = \PageModel::findByPk($elementModel->redirectSite)) !== null)) {
                                $url = $jumpTo->getFrontendUrl();
                                $element->setRedirectSite($url);
                            }

                            $chart->addElement($element);
                        }
                    }
                } else {
                    throw new UnknownChartException();
                }

                $response = new JsonResponse($chart->createEncodableArray(), Response::HTTP_OK);
            } else {
                $response = new Response('', Response::HTTP_UNAUTHORIZED);
            }
        } catch (UnknownChartException $exception) {
            $response = new Response('', Response::HTTP_NOT_FOUND);
            $this->log($exception);
        } catch (UnknownChartSourceException $exception) {
            $response = new Response('', Response::HTTP_NOT_FOUND);
            $this->log($exception);
        } catch (EmptyChartException $exception) {
            $response = new Response('', Response::HTTP_NOT_FOUND);
            $this->log($exception);
        } catch (\Throwable $throwable) {
            $response = new Response('', Response::HTTP_INTERNAL_SERVER_ERROR);
            $this->log($throwable);
        }

        return $response;
    }

    private function log(\Throwable $throwable) {
        C4gLogModel::addLogEntry(
            'Visualization',
            "Message: " . $throwable->getMessage() .
            "\n" .
            "Trace: " . $throwable->getTraceAsString() .
            "\n" .
            "File: " . $throwable->getFile() .
            "\n" .
            "Line: " . $throwable->getLine()
        );
    }

    private function authorized() : bool {
        //Todo implement
        return true;
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
                $where .= $model->whereColumn . ' ' . $comparison . ' ' . $model->whereValue;
            }
            return $where;
        } else {
            return '';
        }
    }
}



