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
namespace con4gis\VisualizationBundle\Controller;

use con4gis\CoreBundle\Controller\BaseController;
use con4gis\VisualizationBundle\Classes\Exceptions\EmptyChartException;
use con4gis\VisualizationBundle\Classes\Exceptions\UnknownChartException;
use con4gis\VisualizationBundle\Classes\Exceptions\UnknownChartSourceException;
use con4gis\VisualizationBundle\Classes\Services\ChartBuilderService;
use Contao\CoreBundle\Framework\ContaoFramework;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartController extends BaseController
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
        ContainerInterface $container,
        ContaoFramework $framework,
        LoggerInterface $logger,
        ChartBuilderService $chartBuilder
    ) {
        $this->logger = $logger;
        $this->chartBuilder = $chartBuilder;
        parent::__construct($container);
        $framework->initialize(true);
    }

    protected function initialize($withEntityManager = true)
    {
        parent::initialize(false);
    }

    /**
     * @param Request $request
     * @param $chartId
     * @return JsonResponse
     * @Route("/con4gis/fetchChart/{chartId}", methods={"GET"})
     */
    public function getFetchChartAction(Request $request, $chartId): JsonResponse
    {
        try {
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



