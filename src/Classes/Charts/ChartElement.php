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
namespace con4gis\VisualizationBundle\Classes\Charts;

use con4gis\VisualizationBundle\Classes\Labels\Label;
use con4gis\VisualizationBundle\Classes\Source\Source;
use con4gis\VisualizationBundle\Classes\Transformers\Transformer;
use Contao\Controller;

class ChartElement
{
    const TYPE_BAR = 'bar';
    const TYPE_LINE = 'line';
    const TYPE_AREA_LINE = 'area';
    const TYPE_SPLINE = 'spline';
    const TYPE_AREA_SPLINE = 'areaspline';
    const TYPE_AREA = 'area';
    const TYPE_PIE = 'pie';
    const TYPE_DONUT = 'donut';
    const TYPE_GAUGE = 'gauge';
    const TYPE_GANTT = 'gantt';

    const ORIGIN_INPUT = '1';
    const ORIGIN_TABLE = '2';
    const ORIGIN_PERIOD = '3';

    protected $type;
    protected $source;
    protected $transformers = [];
    protected $labels = [];
    
    protected $x = 'x';
    protected $y = 'y';
    protected $yLabelCount = 1;
    protected $yAxisSelection = "y1";
//    protected $yRotate = ;

    protected $x2 = 'x2'; //for gantt charts

    //protected $showInLegend = true;
    protected $name = '';
    protected $group = -1;
    protected $color = '';

    protected $mapTimeValues = false;
    protected $dateTimeFormat = '';
    
    /**
     * @var CoordinateSystem
     */
    protected $coordinateSystem = null;
    
    protected $toolTip = null;

    protected $redirectSite = '';

    protected $decimalPoints = 2;
    protected $showEmptyYValues = true;
    
    protected $tooltipExtension = "";

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
            $yValue = round(floatval($this->y), intval($this->decimalPoints)) ? $this->y : round(floatval($entry->get($this->y)), intval($this->decimalPoints));
            $xValue = round(floatval($this->x), intval($this->decimalPoints)) ? $this->x : round(floatval($entry->get($this->x)), intval($this->decimalPoints));
    
            if (!$this->showEmptyYValues && $yValue === 0.0) {
                continue;
            }
    
            $dataPoints[] = [
                'x' => $xValue,
                'y' => $yValue,
                'min' => $entry->get('min'),
                'redirect' => $entry->get('redirectSite'),
            ];

            $xstart = $xValue;
            $xend = round(floatval($this->x2), intval($this->decimalPoints)) ? $this->x2 : round(floatval($entry->get($this->x2)), intval($this->decimalPoints));

            if ($xstart && $xend && ($xend > $xstart)) {
                $dataPoints[] = [
                    'x' => $xend,
                    'y' => $yValue,
                    'min' => $entry->get('min'),
                    'redirect' => $entry->get('redirectSite'),
                ];

//               //ToDo configuration param
//               //$factor = ($xend-$xstart) / 2;
//               if (($xend-$xstart) > 57600) {
//                   $factor = 28800;
//               }
//               if (($xend-$xstart) > 172800) {
//                   $factor = 86400;
//               }
//               if (($xend-$xstart) > 1209600) {
//                   $factor = 604800;
//               }
//               if (($xend-$xstart) > 5184000) {
//                   $factor = 2592000;
//               }
//               for ($i=$xstart; $i <= $xend; $i+=$factor) {
//                    $dataPoints[] = [
//                        'x' => $i,
//                        'y' => $yValue,
//                        'min' => $entry->get('min'),
//                        'redirect' => $entry->get('redirectSite')
//                    ];
//                }
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

            foreach ($dataPoints as $key => $dataPoint) {
                $dataPoints[$key]['x'] = intval($dataPoints[$key]['x']);
                $tstamp = $dataPoints[$key]['x'];
                if ($tstamp === 1) {
                    $tstamp = 0;
                }
                
                if ($tstamp != $oldstamp) {
                    $i++;
                }
    
                if ($datetime->getTimezone()->getName() !== "UTC") {
                    $tstamp -= 3600;
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
            'dataPoints' => $dataPoints,
        ];
    
        if ($this->yAxisSelection === "y1") {
            $result['target'] = "y";
        } else if ($this->yAxisSelection === "y2") {
            $result['target'] = "y2";
        }

        $group = ($this->group >= 0) ? $this->group : false; //ToDo different groups for intervals
        $name = ($this->name !== '') ? $this->name : false;

        if ($group) {
            $result['group'] = $group;
        }

        if ($name) {
            $result['name'] = html_entity_decode($name);
        }
        
        if ($this->tooltipExtension) {
            $result['tooltipExtension'] = Controller::replaceInsertTags($this->tooltipExtension);
        }
        
        if ($this->mapTimeValues) {
            $result['xType'] = "datetime";
            $result['dateTimeFormat'] = $dateTimeFormat;
        } else {
            $result['xType'] = "nominal";
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

    /**
     * @param string $redirectSite
     */
    public function setRedirectSite(string $redirectSite): ChartElement
    {
        $this->redirectSite = $redirectSite;

        return $this;
    }

    /**
     * @return int
     */
    public function getDecimalPoints(): int
    {
        return $this->decimalPoints;
    }

    /**
     * @param int $decimalPoints
     */
    public function setDecimalPoints(int $decimalPoints): void
    {
        $this->decimalPoints = $decimalPoints;
    }
    
    /**
     * @return bool
     */
    public function isShowEmptyYValues(): bool
    {
        return $this->showEmptyYValues;
    }
    
    /**
     * @param bool $showEmptyYValues
     */
    public function setShowEmptyYValues(bool $showEmptyYValues): void
    {
        $this->showEmptyYValues = $showEmptyYValues;
    }
    
    /**
     * @return int
     */
    public function getYLabelCount(): int
    {
        return $this->yLabelCount;
    }
    
    /**
     * @param int $yLabelCount
     */
    public function setYLabelCount(int $yLabelCount): void
    {
        $this->yLabelCount = $yLabelCount;
    }
    
    /**
     * @return string
     */
    public function getYAxisSelection(): string
    {
        return $this->yAxisSelection;
    }
    
    /**
     * @param string $yAxisSelection
     */
    public function setYAxisSelection(string $yAxisSelection): void
    {
        $this->yAxisSelection = $yAxisSelection;
    }
    
    /**
     * @return string
     */
    public function getTooltipExtension(): string
    {
        return $this->tooltipExtension;
    }
    
    /**
     * @param string $tooltipExtension
     */
    public function setTooltipExtension(string $tooltipExtension): void
    {
        $this->tooltipExtension = $tooltipExtension;
    }
}
