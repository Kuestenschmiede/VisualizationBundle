<?php

namespace con4gis\VisualizationBundle\Classes\Charts;

use con4gis\VisualizationBundle\Classes\Labels\Label;
use con4gis\VisualizationBundle\Classes\Source\Source;
use con4gis\VisualizationBundle\Classes\Transformers\Transformer;
use Symfony\Component\HttpFoundation\Response;

class ChartElement
{
    const TYPE_BAR = 'bar';
    const TYPE_LINE = 'line';
    const TYPE_SPLINE = 'spline';
    const TYPE_AREA = 'area';
    const TYPE_PIE = 'pie';
    const TYPE_DONUT = 'donut';
    const TYPE_GAUGE = 'gauge';
    const TYPE_GANTT = 'gantt'; //unready


    const ORIGIN_INPUT = '1';
    const ORIGIN_TABLE = '2';

    protected $type;
    protected $source;
    protected $transformers = [];
    protected $labels = [];

    protected $x = 'x';
    protected $y = 'y';

    protected $x2 = 'x2'; //for gantt charts

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

    public function createEncodableArray()
    {
        return $this->createEncodableDataPointsArray();
    }

    private function createEncodableDataPointsArray()
    {
        $dataPoints = [];

        foreach ($this->source as $entry) {
            $dataPoints[] = [
                'x' => $entry->get($this->x),
                'y' => $entry->get($this->y)
            ];

            $tstamp = intval($entry->get($this->x));
            $tstamp2 = intval($entry->get($this->x2));

           if ($tstamp && $tstamp2 && ($tstamp2 > $tstamp)) {
                for ($i=$tstamp+1; $i <= $tstamp2; $i+=3600) {
                    $count++;
                    $dataPoints[] = [
                        'x' => $i,
                        'y' => $entry->get($this->y)
                    ];
                }
            }
        }

        foreach ($this->transformers as $transformer) {
            $dataPoints = $transformer->transform($dataPoints);
        }

        $labelArr = $dataPoints;
        if ($this->mapTimeValues === true) {
            $datetime = new \DateTime();
            $map = [];
            $count = $this->xLabelCount;
            $i = 0;
            $oldFormat = '';
            $oldstamp = '';

            foreach ($dataPoints as $key=>$dataPoint) {
                $dataPoints[$key]['x'] = intval($dataPoints[$key]['x']);
                $tstamp = $dataPoints[$key]['x'];

                if ($tstamp != $oldstamp) {
                    $i++;
                }

                $datetime->setTimestamp($tstamp);
                $map[$tstamp] = $datetime->format($this->dateTimeFormat);

                if ($oldFormat != $map[$tstamp]) {
                    if (($i % $count == 0) || ($i == 1)) {
                        $this->coordinateSystem->x()->setTickValue($tstamp, $map[$tstamp], $this->xRotate);
                    }
                }

                $oldFormat = $map[$tstamp];
                $oldstamp = $tstamp;
            }

            foreach ($map as $key => $value) {
                $this->toolTip->setTitle($key, $value);
            }
        }

        foreach ($this->labels as $label) {
            $dataPoints = $label->label($dataPoints);
        }


        $result = [
            'type' => $this->type,
            'dataPoints' => $dataPoints
        ];

        $group = ($this->group >= 0) ? $this->group : false; //ToDo different groups for intervals
        $name = ($this->name !== '') ? $this->name : false;

        if ($group) {
            $result['group'] = $group;
        }

        if ($name) {
            $result['name'] = $name;
        }

        return $result;
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
     * @param string $x2
     */
    public function setX2(string $x2): ChartElement
    {
        $this->x2 = $x2;

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

    public function addTransformer(Transformer $transformer)
    {
        $this->transformers[] = $transformer;

        return $this;
    }

    public function transform(Transformer $transformer)
    {
        return $this->addTransformer($transformer);
    }

    public function addLabel(Label $label)
    {
        $this->labels[] = $label;

        return $this;
    }

    public function label(Label $label)
    {
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
        $this->color = '#' . $color;

        return $this;
    }

    public function mapTimeValues(string $dateTimeFormat, CoordinateSystem $coordinateSystem, Tooltip $tooltip, int $xLabelCount = 1, int $xRotate = 0)
    {
        $this->mapTimeValues = true;
        $this->dateTimeFormat = $dateTimeFormat;
        $this->coordinateSystem = $coordinateSystem;
        $this->toolTip = $tooltip;
        $this->xLabelCount = $xLabelCount;
        $this->xRotate = $xRotate;
    }
}
