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
    const TYPE_LINE = 'line';
    const TYPE_AREA = 'area';
    const TYPE_PIE = 'pie';

    const ORIGIN_INPUT = '1';
    const ORIGIN_TABLE = '2';

    protected $type;
    protected $source;
    protected $transformers = [];
    protected $labels = [];

    protected $x = 'x';
    protected $y = 'y';

    protected $showInLegend = true;
    protected $name = '';
    protected $group = -1;
    protected $color = '';

    protected $mapTimeValues = false;
    protected $dateTimeFormat = '';
    protected $coordinateSystem = null;
    protected $toolTip = null;

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

//        if ($showLegend === true && $this->showInLegend === true) {
//            $array['showInLegend'] = true;
//        }

        if ($this->name !== '') {
            $array['name'] = $this->name;
        }

        if ($this->group >= 0) {
            $array['group'] = $this->group;
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

        if ($this->mapTimeValues === true) {
            $datetime = new \DateTime();
            $map = [];
            foreach ($dataPoints as $dataPoint) {
                $tstamp = $dataPoint['x'];
                $datetime->setTimestamp($tstamp);
                $map[$tstamp] = $datetime->format($this->dateTimeFormat);
                if ($datetime->format('d') === '01') {
                    $this->coordinateSystem->x()->setTickValue($tstamp, $map[$tstamp]);
                }
            }
            foreach ($map as $key => $value) {
                $this->toolTip->setTitle($key, $value);
            }
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

    /**
     * @param int $group
     * @return ChartElement
     */
    public function setGroup(int $group): ChartElement
    {
        $this->group = $group;
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

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return ChartElement
     */
    public function setColor(string $color): ChartElement
    {
        $this->color = '#'.$color;
        return $this;
    }

    public function mapTimeValues(string $dateTimeFormat, CoordinateSystem $coordinateSystem, Tooltip $tooltip) {
        $this->mapTimeValues = true;
        $this->dateTimeFormat = $dateTimeFormat;
        $this->coordinateSystem = $coordinateSystem;
        $this->toolTip = $tooltip;
    }
}