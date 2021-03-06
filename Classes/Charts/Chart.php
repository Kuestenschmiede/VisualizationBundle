<?php
/**
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    7
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
 */
namespace con4gis\VisualizationBundle\Classes\Charts;

class Chart
{
    const RANGE_DEFAULT = 'range_default';
    const RANGE_ALL = 'range_all';

    protected $zoom = false;
    protected $points = true;
    protected $legend = true;
    protected $tooltips = true;
    protected $labels = false;
    protected $oneLabelPerElement = false;

    protected $showLegend = false;
    protected $legendFontSize = 16;
    protected $legendInteractive = true;

    protected $elements = [];
    protected $ranges = [];

    protected $groups = [];

    protected $coordinateSystem = null;
    protected $tooltip = null;

    /**
     * @return array
     */
    public function createEncodableArray()
    {
        $array = [
            'colors' => $this->getColors(),
            'ranges' => $this->ranges,
            'data' => $this->createEncodableDataArray(),
            ];

        if ($this->showLegend === true) {
            $array['legend'] = [
                'cursor' => 'pointer',
                'fontsize' => $this->legendFontSize,
                'interactive' => $this->legendInteractive,
            ];
        }

        $array['zoom'] = ['enabled' => boolval($this->zoom)];
        $array['points'] = ['enabled' => boolval($this->points)];
        $array['legend'] = ['enabled' => boolval($this->legend)];
        $array['tooltips'] = ['enabled' => boolval($this->tooltips)];
        $array['labels'] = ['enabled' => boolval($this->labels)];
        $array['oneLabelPerElement'] = ['enabled' => boolval($this->oneLabelPerElement)];

        if ($this->coordinateSystem instanceof CoordinateSystem === true) {
            $array['axis'] = $this->coordinateSystem->createEncodableArray();
        }

        if ($this->tooltip instanceof Tooltip === true) {
            $array['tooltip'] = $this->tooltip->createEncodableArray();
        }

        return $array;
    }

    private function createEncodableDataArray()
    {
        $data = [];
        foreach ($this->elements as $element) {
            $data[] = $element->createEncodableArray();
        }

        return $data;
    }

    public function addElement(ChartElement $element)
    {
        $this->elements[] = $element;

        return $this;
    }

    public function addRange(string $name, float $lowerBound, float $upperBound, bool $default = false)
    {
        if (($default === true) && (empty($this->ranges[static::RANGE_DEFAULT]) === true)) {
            $this->ranges[static::RANGE_DEFAULT] = [
                'lowerBound' => $lowerBound,
                'upperBound' => $upperBound,
            ];
        } else {
            $this->ranges[$name] = [
                'lowerBound' => $lowerBound,
                'upperBound' => $upperBound,
            ];
        }
    }

    public function group(string $group)
    {
        if (!isset($this->groups[$group]) === true) {
            $this->groups[$group] = sizeof($this->groups);
        }

        return $this->groups[$group];
    }

    public function legend(int $fontsize = 16, bool $interactive = true)
    {
        $this->showLegend = true;
        $this->legendFontSize = $fontsize;
        $this->legendInteractive = $interactive;

        return $this;
    }

    public function getColors()
    {
        $colors = [];
        foreach ($this->elements as $element) {
            $colors[] = $element->getColor();
        }

        return $colors;
    }

    /**
     * @param bool $zoom
     * @return Chart
     */
    public function setZoom($zoom = true): Chart
    {
        $this->zoom = boolval($zoom);

        return $this;
    }

    /**
     * @param bool $points
     */
    public function setPoints($points = true): Chart
    {
        $this->points = boolval($points);

        return $this;
    }

    /**
     * @param bool $legend
     */
    public function setLegend($legend = true): Chart
    {
        $this->legend = boolval($legend);

        return $this;
    }

    /**
     * @param bool $tooltips
     */
    public function setTooltips($tooltips = false): Chart
    {
        $this->tooltips = boolval($tooltips);

        return $this;
    }

    /**
     * @param bool $labels
     */
    public function setLabels($labels = false): Chart
    {
        $this->labels = boolval($labels);

        return $this;
    }

    /**
     * @param bool $oneLabelPerElement
     */
    public function setOneLabelPerElement($oneLabelPerElement = false): Chart
    {
        $this->oneLabelPerElement = boolval($oneLabelPerElement);

        return $this;
    }

    /**
     * @param string $theme
     * @return Chart
     */
    public function setTheme(string $theme): Chart
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @param null $coordinateSystem
     * @return Chart
     */
    public function setCoordinateSystem($coordinateSystem)
    {
        $this->coordinateSystem = $coordinateSystem;

        return $this;
    }

    /**
     * @param Tooltip $tooltip
     * @return $this
     */
    public function setTooltip(Tooltip $tooltip)
    {
        $this->tooltip = $tooltip;

        return $this;
    }
}
