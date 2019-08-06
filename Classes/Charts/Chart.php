<?php


namespace con4gis\VisualizationBundle\Classes\Charts;

use con4gis\VisualizationBundle\Classes\Elements\Element;
use con4gis\VisualizationBundle\Classes\Exceptions\InvalidChartTypeException;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;

abstract Class Chart
{
    const TYPE_PIE = 10;
    const TYPE_LINE = 20;

    protected $elements = [];

    /**
     * Chart constructor.
     * @param ChartModel $model
     */
    public function __construct(ChartModel $model) {}

    /**
     * @param ChartModel $model
     * @return LineChart|PieChart
     * @throws InvalidChartTypeException
     */
    public static final function create(ChartModel $model) {
        //Todo is the value in the model an int or a string? We may have to adjust the constant values.

        switch ($model->chartType) {
            case self::TYPE_PIE:
                return new PieChart($model);
                break;
            case self::TYPE_LINE:
                return new LineChart($model);
                break;
            default:
                throw new InvalidChartTypeException();
        }
    }

    /**
     * @return string
     */
    public abstract function getHTML();

    public function setElements(array $elements) {
        foreach ($elements as $element) {
            $this->addElement($element);
        }
        return $this;
    }

    public function addElement(Element $element) {
        $this->elements[] = $element;
    }
}
