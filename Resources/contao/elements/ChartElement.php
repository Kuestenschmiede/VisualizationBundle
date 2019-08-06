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

use con4gis\VisualizationBundle\Classes\Charts\Chart;
use con4gis\VisualizationBundle\Classes\Elements\Element;
use con4gis\VisualizationBundle\Classes\Exceptions\InvalidChartTypeException;
use con4gis\VisualizationBundle\Classes\Exceptions\InvalidElementTypeException;
use con4gis\VisualizationBundle\Classes\Exceptions\InvalidSourceTypeException;
use con4gis\VisualizationBundle\Classes\Exceptions\UndefinedChartDataOriginException;
use con4gis\VisualizationBundle\Resources\contao\models\ChartInputModel;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;
use Contao\ContentElement;
use Throwable;

class ChartElement extends ContentElement
{

    protected $strTemplate = 'c4g_visualization_chart';

    public function generate()
    {
        if (TL_MODE == 'BE') {
            return '';
        }

        return parent::generate();
    }

    protected function compile()
    {
        $elementModel = $this->getModel();
        $chartModel = ChartModel::findByPk($elementModel->chartId);

        // Fetch some data from somewhere, preferably a model
        try {
            switch ($chartModel->dataOrigin) {
                case 'input':
                    $data = ChartInputModel::findByChartId($chartModel->id);
                    break;
                case 'table':
                    //Todo Load data from some other table
                    $data = [];
                    break;
                default:
                    throw new UndefinedChartDataOriginException();
                    break;
            }


            $chart = Chart::create($chartModel);
            $chart->setElements(Element::create($chartModel, $data));

            $this->Template->chartData = $chart->getHTML();
        } catch (UndefinedChartDataOriginException $e) {
            //Todo Error handling
        } catch (InvalidChartTypeException $e) {
                //Todo Error handling
        } catch (InvalidElementTypeException $e) {
            //Todo Error handling
        } catch (InvalidSourceTypeException $e) {
            //Todo Error handling
        }
    }
}
