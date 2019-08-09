<?php


namespace con4gis\VisualizationBundle\Classes\Charts;

use con4gis\VisualizationBundle\Classes\Charts\Chart;
use con4gis\VisualizationBundle\Classes\Exceptions\InvalidElementTypeException;
use con4gis\VisualizationBundle\Classes\Exceptions\InvalidSourceTypeException;
use con4gis\VisualizationBundle\Classes\Labels\Label;
use con4gis\VisualizationBundle\Classes\Source\Source;
use con4gis\VisualizationBundle\Classes\Transformers\Transformer;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;

class ChartElement
{
    const TYPE_BAR = 'bar';
    const TYPE_COLUMN = 'column';
    const TYPE_LINE = 'line';
    const TYPE_AREA = 'area';
    const TYPE_PIE = 'pie';

    protected $type;
    protected $source;
    protected $transformers = [];
    protected $labels = [];

    protected $x = 'x';
    protected $y = 'y';

    protected $showInLegend = true;
    protected $name = '';

    public function __construct(string $type, Source $source)
    {
        $this->type = $type;
        $this->source = $source;
    }

    public function createEncodableArray($showLegend = false) {
        $array = [
            'type' => $this->type,
            'dataPoints' => $this->createEncodableDataPointsArray()
        ];

        if ($showLegend === true && $this->showInLegend === true) {
            $array['showInLegend'] = true;
        }

        if ($this->name !== '') {
            $array['name'] = $this->name;
        }

        return $array;
    }

    private function createEncodableDataPointsArray() {
        $dataPoints = [];
        foreach ($this->source as $entry) {
            $dataPoints[] = [
                'x' => $entry->get($this->x),
                'y' => $entry->get($this->y),
            ];
        }

        foreach ($this->transformers as $transformer) {
            $dataPoints = $transformer->transform($dataPoints);
        }

        foreach ($this->labels as $label) {
            $dataPoints = $label->label($dataPoints);
        }

        return $dataPoints;
    }

    /**
     * @param string $x
     * @return ChartElement
     */
    public function setX(string $x): ChartElement
    {
        $this->x = $x;
        return $this;
    }

    /**
     * @param string $y
     * @return ChartElement
     */
    public function setY(string $y): ChartElement
    {
        $this->y = $y;
        return $this;
    }

    /**
     * @param bool $showInLegend
     * @return ChartElement
     */
    public function setShowInLegend(bool $showInLegend = true): ChartElement
    {
        $this->showInLegend = $showInLegend;
        return $this;
    }

    /**
     * @param string $name
     * @return ChartElement
     */
    public function setName(string $name): ChartElement
    {
        $this->name = $name;
        return $this;
    }

    public function addTransformer(Transformer $transformer) {
        $this->transformers[] = $transformer;
        return $this;
    }

    public function transform(Transformer $transformer) {
        return $this->addTransformer($transformer);
    }

    public function addLabel(Label $label) {
        $this->labels[] = $label;
        return $this;
    }

    public function label(Label $label) {
        return $this->addLabel($label);
    }

}