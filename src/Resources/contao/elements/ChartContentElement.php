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
namespace con4gis\VisualizationBundle\Resources\contao\elements;

use con4gis\CoreBundle\Classes\ResourceLoader;
use con4gis\VisualizationBundle\Classes\Charts\Chart;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartRangeModel;
use Contao\BackendTemplate;
use Contao\ContentElement;
use Contao\FilesModel;
use Contao\StringUtil;
use Contao\System;

class ChartContentElement extends ContentElement
{
    protected $strTemplate = 'c4g_visualization_chart';
    protected static $instances = 1;

    protected function compile()
    {
        if (System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create('')))
        {
            $chartModel = ChartModel::findByPk($this->getModel()->chartID);
            $this->strTemplate          = 'be_wildcard';
            $this->Template             = new BackendTemplate($this->strTemplate);
            $this->Template->title      = $this->headline;
            $this->Template->wildcard   = "### $chartModel->backendtitle ###";
        } else if (!$this->getModel()->invisible) {
            ResourceLoader::loadJavaScriptResource('bundles/con4gisvisualization/dist/js/d3.min.js', ResourceLoader::BODY);
            ResourceLoader::loadJavaScriptResource('bundles/con4gisvisualization/build/c4g_visualization.js', ResourceLoader::BODY);
            ResourceLoader::loadCssResource('bundles/con4gisvisualization/dist/css/c4g_visualization.min.css');
            ResourceLoader::loadCssResource('bundles/con4gisvisualization/dist/css/billboard.min.css');
            $elementModel = $this->getModel();
            $chartId = $elementModel->chartID;
            $chartModel = ChartModel::findByPk($chartId);
            $this->Template->published = $chartModel->published;
            if ($chartModel->published === '1') {
                $fileModel = FilesModel::findByUuid($chartModel->image);
                $headline = StringUtil::deserialize($elementModel->headline, true);
                $this->Template->chartID = $chartId;
                $this->Template->headline = $headline;
                $this->Template->path = $fileModel->path;
                $meta = StringUtil::deserialize($fileModel->meta, true);
                global $objPage;
                $this->Template->imageAlt = $meta[$objPage->language]['alt'] ?? '';

                $this->Template->imageMaxHeight = $chartModel->imageMaxHeight;
                $this->Template->imageMaxWidth = $chartModel->imageMaxWidth;
                $this->Template->imageMarginTop = $chartModel->imageMarginTop;
                $this->Template->imageMarginLeft = $chartModel->imageMarginLeft;
                $this->Template->imageOpacity = 1 - ($chartModel->imageOpacity / 100);

                $this->Template->cssClass = $chartModel->cssClass;
                
                $cssID = StringUtil::deserialize($elementModel->cssID, true);
                if ($cssID) {
                    if ($cssID[0] !== "") {
                        $this->Template->outerID = $cssID[0];
                    }
                    if ($cssID[1] !== "") {
                        $this->Template->outerClass = $cssID[1];
                    }
                }

                $buttons = [];
                $rangeModels = ChartRangeModel::findByChartId($chartId);

                if (($chartModel->buttonAllCaption !== '') && ($chartModel->buttonAllPosition === '1')) {
                    $buttons[] = [
                        'range' => Chart::RANGE_ALL,
                        'target' => 'c4g_chart_' . static::$instances,
                        'caption' => $chartModel->buttonAllCaption
                    ];
                }

                $defaultDefined = false;
                if ($rangeModels) {
                    foreach ($rangeModels as $model) {
                        if (($model->defaultRange === '1') && ($defaultDefined === false)) {
                            $defaultDefined = true;
                            $buttons[] = [
                                'range' => Chart::RANGE_DEFAULT,
                                'target' => 'c4g_chart_' . static::$instances,
                                'caption' => $model->name
                            ];
                        } else {
                            $buttons[] = [
                                'range' => $model->name,
                                'target' => 'c4g_chart_' . static::$instances,
                                'caption' => $model->name
                            ];
                        }
                    }
                }
                
                if (($chartModel->buttonAllCaption !== '') && ($chartModel->buttonAllPosition === '2')) {
                    $buttons[] = [
                        'range' => Chart::RANGE_ALL,
                        'target' => 'c4g_chart_' . static::$instances,
                        'caption' => $chartModel->buttonAllCaption
                    ];
                }

                $this->Template->buttons = $buttons;
                $this->Template->buttonPosition = $chartModel->buttonPosition;

                $this->Template->instance = static::$instances;
                static::$instances += 1;
            }
        }
    }
}
