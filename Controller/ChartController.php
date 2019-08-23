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
namespace con4gis\VisualizationBundle\Controller;

use con4gis\VisualizationBundle\Classes\Charts\Chart;
use con4gis\VisualizationBundle\Classes\Charts\ChartElement;
use con4gis\VisualizationBundle\Classes\Exceptions\EmptyChartException;
use con4gis\VisualizationBundle\Classes\Exceptions\UnknownChartException;
use con4gis\VisualizationBundle\Classes\Exceptions\UnknownChartSourceException;
use con4gis\VisualizationBundle\Classes\Source\Source;
use con4gis\VisualizationBundle\Resources\contao\models\ChartElementInputModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartElementModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartRangeModel;
use Contao\Database;
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
            if ($this->authorized() === true) {
                $chartModel = ChartModel::findByPk($chartId);
                if ($chartModel instanceof ChartModel === true) {
                    $chart = new Chart();

                    $rangeModels = ChartRangeModel::findByChartId($chartId);
                    foreach ($rangeModels as $model) {
                        $chart->addRange($model->name, $model->fromX, $model->toX, $model->defaultRange === '1');
                    }

                    $elementModels = ChartElementModel::findByChartId($chartId);
                    if ($elementModels === null) {
                        throw new EmptyChartException();
                    }

                    foreach ($elementModels as $elementModel) {
                        switch ($elementModel->origin) {
                            case ChartElement::ORIGIN_INPUT:
                                $inputModels = ChartElementInputModel::findByElementId($elementModel->id);
                                if ($inputModels !== null) {
                                    $source = new Source($inputModels);

                                }
                                break;
                            case ChartElement::ORIGIN_TABLE:
                                $table = $elementModel->table;
                                $database = Database::getInstance();
                                $stmt = $database->prepare("SELECT * FROM ".$table);
                                $result = $stmt->execute();
                                $source = new Source($result->fetchAllAssoc());
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
                        }
                        $chart->addElement($element);
                    }
                } else {
                    throw new UnknownChartException();
                }

                $response = new JsonResponse($chart->createEncodableArray(), Response::HTTP_OK);
            } else {
                $response = new Response('', Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Throwable $throwable) {
            //Todo Error handling
            $response = new Response($throwable->getMessage() .
                "\n" .
                $throwable->getTraceAsString() .
                "\n ".
                $throwable->getFile() .
                "\n ".
                $throwable->getLine()
                , Response::HTTP_NOT_FOUND);
            //$response = new Response('', Response::HTTP_NOT_FOUND);
        }

        return $response;
    }

    private function authorized() : bool {
        //Todo implement
        return true;
    }
}



