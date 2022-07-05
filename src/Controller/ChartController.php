<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2022, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */
namespace con4gis\VisualizationBundle\Controller;

use con4gis\CoreBundle\Controller\BaseController;
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
use con4gis\VisualizationBundle\Resources\contao\models\ChartElementPeriodModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartRangeModel;
use con4gis\VisualizationBundle\Services\ChartBuilderService;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Database;
use Contao\Model\Collection;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartController extends AbstractController
{
    private $framework = null;
    
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * @var ChartBuilderService
     */
    private $chartBuilder;

    public function __construct(
        ContaoFramework $framework,
        LoggerInterface $logger,
        ChartBuilderService $chartBuilder
    ) {
        $this->framework = $framework;
        $this->logger = $logger;
        $this->chartBuilder = $chartBuilder;
    }

    /**
     * @param Request $request
     * @param $chartId
     * @return JsonResponse
     * @Route("/visualization-api/fetchChart/{chartId}", methods={"GET"})
     */
    public function getFetchChartAction(Request $request, $chartId): JsonResponse
    {
        try {
            $this->framework->initialize(true);
            if ($chartId/* && $this->authorized() === true*/) {
                $chartId = intval($chartId);
                $chart = $this->chartBuilder->createChartFromId($chartId);

                $response = new JsonResponse($chart->createEncodableArray(), Response::HTTP_OK);
                if (extension_loaded('zlib')) {
                    $response->setContent(gzdeflate($response->getContent()));
                    $response->headers->set('Content-encoding', 'deflate');
                }
            } else {
                $response = new JsonResponse('', Response::HTTP_UNAUTHORIZED);
            }
        } catch (UnknownChartException|UnknownChartSourceException|EmptyChartException $exception) {
            $response = new JsonResponse('', Response::HTTP_NOT_FOUND);
            $this->log($exception);
        } catch (\Throwable $throwable) {
            $response = new JsonResponse('', Response::HTTP_INTERNAL_SERVER_ERROR);
            $this->log($throwable);
        }

        return $response;
    }

    private function log(\Throwable $throwable) {
        $this->logger->error($throwable->getMessage() . "\n" . $throwable->getTraceAsString());
    }

    private function authorized() : bool {
        //Todo implement
        return true;
    }
}



