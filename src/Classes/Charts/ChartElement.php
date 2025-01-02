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
namespace con4gis\VisualizationBundle\Classes\Charts;

use con4gis\CoreBundle\Classes\C4GUtils;
use con4gis\VisualizationBundle\Classes\Labels\Label;
use con4gis\VisualizationBundle\Classes\Source\Source;
use con4gis\VisualizationBundle\Classes\Transformers\Transformer;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;
use Contao\Config;
use Contao\Controller;

class ChartElement
{
    public const TYPE_BAR = 'bar';
    public const TYPE_LINE = 'line';
    public const TYPE_AREA_LINE = 'area';
    public const TYPE_SPLINE = 'spline';
    public const TYPE_AREA_SPLINE = 'areaspline';
    public const TYPE_AREA = 'area';
    public const TYPE_PIE = 'pie';
    public const TYPE_DONUT = 'donut';
    public const TYPE_GAUGE = 'gauge';
    public const TYPE_GANTT = 'gantt';

    public const ORIGIN_INPUT = '1';
    public const ORIGIN_TABLE = '2';
    public const ORIGIN_PERIOD = '3';

    private const TICK_MODE_NTH = 'nth';
    private const TICK_MODE_MONTHLY = 'monthly';
    private const TICK_MODE_YEARLY = 'yearly';

    private string $type;
    private Source $source;
    private array $transformers = [];
    private array $labels = [];
    private string $x = 'x';
    private string $y = 'y';
    private int $yLabelCount = 1;
    private string $yAxisSelection = "y1";
    private string $x2 = 'x2';
    private string $name = '';
    private int $group = -1;
    private string $color = '';
    private bool $mapTimeValues = false;
    private string $dateTimeFormat = '';
    private string $dateTimeFormatAll = '';
    private ?CoordinateSystem $coordinateSystem = null;
    private ?Tooltip $toolTip = null;
    private int $decimalPoints = 2;
    private bool $showEmptyYValues = true;
    private string $tooltipExtension = "";
    private int $xLabelCount;
    private int $xLabelCountAll;
    private int $xRotate;
    private string $tickMode = '';

    public function __construct(string $type, Source $source)
    {
        $this->type = $type;
        $this->source = $source;
    }

    public function createEncodableArray()
    {
        $result = [
            'type' => $this->type,
            'dataPoints' => $this->createEncodableDataPointsArray(),
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
            $result['tooltipExtension'] = C4GUtils::replaceInsertTags($this->tooltipExtension);
        }

        if ($this->mapTimeValues) {
            $result['xType'] = "datetime";
        } else {
            $result['xType'] = "nominal";
        }

        return $result;
    }

    private function createEncodableDataPointsArray()
    {
        $dataPoints = [];

        foreach ($this->source as $entry) {
            $yValue = round(floatval($this->y), $this->decimalPoints) ? $this->y : round(floatval($entry->get($this->y)), $this->decimalPoints);
            $xValue = round(floatval($this->x), $this->decimalPoints) ? $this->x : round(floatval($entry->get($this->x)), $this->decimalPoints);

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
            $xend = round(floatval($this->x2), $this->decimalPoints) ? $this->x2 : round(floatval($entry->get($this->x2)), $this->decimalPoints);

            if ($xstart && $xend && ($xend > $xstart)) {
                $dataPoints[] = [
                    'x' => $xend,
                    'y' => $yValue,
                    'min' => $entry->get('min'),
                    'redirect' => $entry->get('redirectSite'),
                ];
            }
        }

        foreach ($this->transformers as $transformer) {
            $dataPoints = $transformer->transform($dataPoints);
        }

        if ($this->mapTimeValues === true) {
            $datetime = new \DateTime();
            $map = [];
            $count = $this->xLabelCount;
            $i = 0;
            $oldFormat = '';
            $oldstamp = 0;

            foreach ($dataPoints as $dataPoint) {
                switch ($this->tickMode) {
                    case self::TICK_MODE_MONTHLY:
                        $tstamp = intval($dataPoint['x']);

                        $datetime->setTimezone(new \DateTimeZone(Config::get("timeZone")));
                        $datetime->setTimestamp($tstamp);
                        $map[$tstamp] = $datetime->format($this->dateTimeFormat);
                        if ($datetime->format('d') !== '01') {
                            continue 2;
                        }

                        if ($this->coordinateSystem && ($oldFormat !== $map[$tstamp])) {
                            $this->coordinateSystem->x()->setTickValue($tstamp, $map[$tstamp], $this->xRotate);
                        }

                        $oldFormat = $map[$tstamp];
                        break;
                    case self::TICK_MODE_YEARLY:
                        $tstamp = intval($dataPoint['x']);

                        $datetime->setTimezone(new \DateTimeZone(Config::get("timeZone")));
                        $datetime->setTimestamp($tstamp);
                        $map[$tstamp] = $datetime->format($this->dateTimeFormat);
                        if ($datetime->format('dm') !== '0101') {
                            continue 2;
                        }

                        if ($this->coordinateSystem && ($oldFormat !== $map[$tstamp])) {
                            $this->coordinateSystem->x()->setTickValue($tstamp, $map[$tstamp], $this->xRotate);
                        }

                        $oldFormat = $map[$tstamp];
                        break;
                    default:
                        $tstamp = intval($dataPoint['x']);
                        if ($tstamp === 1) {
                            $tstamp = 0;
                        }

                        if ($tstamp !== $oldstamp) {
                            $i += 1;
                        }

                        $datetime->setTimezone(new \DateTimeZone(Config::get("timeZone")));
                        $datetime->setTimestamp($tstamp);
                        $map[$tstamp] = $datetime->format($this->dateTimeFormat);

                        if ($this->coordinateSystem && ($oldFormat !== $map[$tstamp])) {
                            if (($i % $count === 0) || ($i === 1)) {
                                $this->coordinateSystem->x()->setTickValue($tstamp, $map[$tstamp], $this->xRotate);
                            }
                        }

                        $oldFormat = $map[$tstamp];
                        $oldstamp = $tstamp;
                        break;
                }
            }

            foreach ($map as $key => $value) {
                $this->toolTip->setTitle($key, $value);
            }
        }
        $ticks = $this->coordinateSystem ? $this->coordinateSystem->x()->getTicks() : false;
        if ($ticks && $this->xLabelCountAll) {
            $map = [];
            for ($i = 0; $i <= count($ticks); $i += $this->xLabelCountAll) {
                $datetime->setTimezone(new \DateTimeZone(Config::get("timeZone")));
                $datetime->setTimestamp($ticks[$i]);
                $map[$ticks[$i]] = $datetime->format($this->dateTimeFormatAll);
                if ($map[$ticks[$i]]) {
                    $this->coordinateSystem->x()->setTickValueAll(intval($ticks[$i]), $map[$ticks[$i]]);
                }
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

    public function mapTimeValues(
        ChartModel $chartModel,
        CoordinateSystem $coordinateSystem,
        Tooltip $tooltip
    ) {
        $this->mapTimeValues = true;
        $this->dateTimeFormat = $chartModel->xTimeFormat;
        $this->coordinateSystem = $coordinateSystem;
        $this->toolTip = $tooltip;
        $this->xLabelCount = $chartModel->xLabelCount;
        $this->xRotate = intval($chartModel->xRotate);
        $this->tickMode = $chartModel->xTickMode;
        $this->dateTimeFormatAll = $chartModel->xTimeFormatAll;
        $this->xLabelCountAll = $chartModel->xLabelCountAll;
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
