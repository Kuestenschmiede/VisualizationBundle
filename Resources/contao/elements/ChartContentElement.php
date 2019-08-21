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
namespace con4gis\VisualizationBundle\Resources\contao\elements;

use con4gis\CoreBundle\Resources\contao\classes\ResourceLoader;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;
use Contao\ContentElement;
use Contao\FilesModel;

class ChartContentElement extends ContentElement
{
    protected $strTemplate = 'c4g_visualization_chart';
    protected static $instances = 1;

    protected function compile()
    {
        if (TL_MODE == 'BE') {
            $chartModel = ChartModel::findByPk($this->getModel()->chartID);
            $this->strTemplate          = 'be_wildcard';
            $this->Template             = new \BackendTemplate($this->strTemplate);
            $this->Template->title      = $this->headline;
            $this->Template->wildcard   = "### $chartModel->backendtitle ###";
        } else {
            ResourceLoader::loadJavaScriptResource('bundles/con4gisvisualization/js/d3.js', ResourceLoader::HEAD);
            ResourceLoader::loadJavaScriptResource('bundles/con4gisvisualization/js/c3.js', ResourceLoader::HEAD);
            ResourceLoader::loadJavaScriptResource('bundles/con4gisvisualization/js/c4g_visualization.js', ResourceLoader::HEAD);
            ResourceLoader::loadCssResource('bundles/con4gisvisualization/css/c3.css');
            ResourceLoader::loadCssResource('bundles/con4gisvisualization/css/c4g_visualization.css');
            $elementModel = $this->getModel();
            $chartId = $elementModel->chartID;
            $chartModel = ChartModel::findByPk($chartId);
            $fileModel = FilesModel::findByUuid($chartModel->image);
            $headline = unserialize($elementModel->headline);
            $this->Template->chartID = $chartId;
            $this->Template->headline = $headline;
            $this->Template->path = $fileModel->path;
            $this->Template->imageMaxHeight = $chartModel->imageMaxHeight;
            $this->Template->imageMaxWidth = $chartModel->imageMaxWidth;
            $this->Template->imageMarginTop = $chartModel->imageMarginTop;
            $this->Template->imageMarginLeft = $chartModel->imageMarginLeft;
            $this->Template->imageOpacity =  1 - ($chartModel->imageOpacity / 100);
            $this->Template->instance = static::$instances;
            static::$instances += 1;
        }
    }
}
