<?php


namespace con4gis\VisualizationBundle\Classes\Elements;


use con4gis\VisualizationBundle\Classes\Charts\Chart;
use con4gis\VisualizationBundle\Classes\Exceptions\InvalidElementTypeException;
use con4gis\VisualizationBundle\Classes\Exceptions\InvalidSourceTypeException;
use con4gis\VisualizationBundle\Classes\Source\Source;
use con4gis\VisualizationBundle\Classes\Source\SourceList;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;

abstract class Element
{
    /**
     * Element constructor.
     * @param ChartModel $model
     * @param Source $source
     */
    public function __construct(ChartModel $model, Source $source) {}

    /**
     * @param ChartModel $model
     * @param $source
     * @return array
     * @throws InvalidElementTypeException
     * @throws InvalidSourceTypeException
     */
    public static final function create(ChartModel $model, $source) {
        $source = Source::create($source);
        $elements = [];

        if ($source instanceof SourceList === true) {
            foreach ($source as $s) {
                $elements[] = self::createChild($model, $s);
            }
        } else {
            $elements[] = self::createChild($model, $source);
        }

        return $elements;
    }

    /**
     * @param ChartModel $model
     * @param Source $source
     * @return Line|PieSlice
     * @throws InvalidElementTypeException
     */
    private static final function createChild(ChartModel $model, Source $source) {
        //Todo is the value in the model an int or a string? We may have to adjust the constant values.

        switch ($model->chartType) {
            case Chart::TYPE_PIE:
                return new PieSlice($model, $source);
                break;
            case Chart::TYPE_LINE:
                return new Line($model, $source);
                break;
            default:
                throw new InvalidElementTypeException();
        }
    }

    /**
     * @return string
     */
    public abstract function getHTML();
}