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
use con4gis\VisualizationBundle\Classes\Labels\AllLabel;
use con4gis\VisualizationBundle\Classes\Labels\Label;
use con4gis\VisualizationBundle\Classes\Source\Source;
use con4gis\VisualizationBundle\Classes\Transformers\Interpolator;
use con4gis\VisualizationBundle\Classes\Transformers\Multiplier;
use con4gis\VisualizationBundle\Resources\contao\models\ChartElementInputModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartElementModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;
use con4gis\VisualizationBundle\Resources\contao\models\ColorSetModel;
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
                    $chart->setTitle($chartModel->title);
                    $colorSetModel = ColorSetModel::findByPk($chartModel->colorSetId);
                    $chart->setColors($colorSetModel->name, $colorSetModel->colors);

                    $elementModels = ChartElementModel::findByChartId($chartId);
                    if ($elementModels === null) {
                        throw new EmptyChartException();
                    }

                    foreach ($elementModels as $elementModel) {
                        switch ($elementModel->source) {
                            case 'input':
                                $inputModels = ChartElementInputModel::findByElementId($elementModel->id);
                                if ($inputModels !== null) {
                                    $chart->addElement(new ChartElement($elementModel->type, new Source($inputModels)));
                                }
                                break;
                            default:
                                throw new UnknownChartSourceException();
                                break;
                        }
                    }
                } else {
                    throw new UnknownChartException();
                }

                $response = new JsonResponse($chart->createEncodableArray(), Response::HTTP_OK, [], true);
            } else {
                $response = new Response('', Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Throwable $throwable) {
            $response = new Response('', Response::HTTP_NOT_FOUND);
        }

        return $response;
    }

    public function testFetchAction(Request $request)
    {
        try {
            $chart = new Chart();
            $chart->setTitle('Test Chart');
            $chart->setColors('testColors', [
                "#2F4F4F",
                "#008080",
                "#2E8B57",
                "#3CB371",
                "#90EE90"
            ]);

            $type1 = 'area';
            $data1 = [];
            $data1[0] = ['x' => 10, 'y' => 71];
            $data1[1] = ['x' => 20, 'y' => 55];
            $data1[2] = ['x' => 30, 'y' => 50];
            $data1[3] = ['x' => 40, 'y' => 65];
            $data1[4] = ['x' => 50, 'y' => 95];
            $data1[5] = ['x' => 60, 'y' => 68];
            $data1[6] = ['x' => 70, 'y' => 28];
            $data1[7] = ['x' => 80, 'y' => 34];
            $data1[8] = ['x' => 90, 'y' => 14];

            $chart->legend()
                ->yAxis('Einnahmen in T€', true, 'T€')
                ->xAxis('Verkaufte Menge');

            $element1 = new ChartElement($type1, new Source($data1));
            $element1->setName('Nicht interpoliert');
            $element2 = new ChartElement('line', new Source($data1));
            $element2->setName('Interpoliert 5, Multipliziert 2')
                ->transform(new Multiplier(2.0))
                ->transform(new Interpolator(5))
                ->label(new AllLabel(Label::Y.'T€'));
//            $element3 = new ChartElement($type1, new Source($data1));
//            $element3->setName('Interpoliert 5')
//                ->transform(new Interpolator(5));


            $chart->addElement($element1);
            $chart->addElement($element2);
//            $chart->addElement($element3);

            $response = new JsonResponse($chart->createEncodableArray(), Response::HTTP_OK);
        } catch (\Throwable $throwable) {
            //Todo Error handling
            //var_dump($throwable);
//            $response = new Response('', Response::HTTP_NOT_FOUND);
            $response = new Response($throwable->getMessage(), Response::HTTP_NOT_FOUND);
        }
        return $response;
    }

    private function authorized() : bool {
        //Todo implement
        return true;
    }
}



