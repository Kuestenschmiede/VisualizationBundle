<?php

namespace con4gis\VisualizationBundle\Classes\Charts;

class Chart
{
    const RANGE_DEFAULT = 'range_default';
    const RANGE_ALL = 'range_all';

    protected $zoom = false;

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

        if ($this->zoom === true) {
            $array['zoom'] = ['enabled' => true];
        }

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
            $data[] = $element->createEncodableArray($this->showLegend);
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
    public function setZoom(bool $zoom = true): Chart
    {
        $this->zoom = $zoom;

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
