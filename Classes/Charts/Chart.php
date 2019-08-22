<?php


namespace con4gis\VisualizationBundle\Classes\Charts;

use con4gis\VisualizationBundle\Classes\Exceptions\InvalidChartTypeException;
use con4gis\VisualizationBundle\Classes\Source\Source;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;

Class Chart
{
    protected $zoom = false;
    protected $axes = [];

    protected $showLegend = false;
    protected $legendFontSize = 16;
    protected $legendInteractive = true;

    protected $elements = [];
    protected $ranges = [];

    protected $groups = [];

    /**
     * @return array
     */
    public function createEncodableArray() {
        $array = [
            'colors' => $this->getColors(),
            'ranges' => $this->ranges,
            'data' => $this->createEncodableDataArray()
            ];

        if ($this->showLegend === true) {
            $array['legend'] = [
                'cursor' => 'pointer',
                'fontsize' => $this->legendFontSize,
                'interactive' => $this->legendInteractive
            ];
        }

        if ($this->zoom === true) {
            $array['zoom'] = ['enabled' => true];
        }

//        if ($this->yAxis instanceof Axis === true) {
//            $array['axisY'] = $this->yAxis->createEncodableAxisArray();
//        }
//
//        if ($this->xAxis instanceof Axis === true) {
//            $array['axisX'] = $this->xAxis->createEncodableAxisArray();
//        }

        return $array;
    }

    private function createEncodableDataArray() {
        $data = [];
        foreach ($this->elements as $element) {
            $data[] = $element->createEncodableArray($this->showLegend);
        }
        return $data;
    }

    public function addElement(ChartElement $element) {
        $this->elements[] = $element;
        return $this;
    }

    public function addRange(string $name, float $lowerBound, float $upperBound) {
        $this->ranges[$name] = [
            'lowerBound' => $lowerBound,
            'upperBound' => $upperBound
        ];
    }

    public function group(string $group) {
        if (!isset($this->groups[$group]) === true) {
            $this->groups[$group] = sizeof($this->groups);
        }
        return $this->groups[$group];
    }

    public function legend(int $fontsize = 16, bool $interactive = true) {
        $this->showLegend = true;
        $this->legendFontSize = $fontsize;
        $this->legendInteractive = $interactive;
        return $this;
    }

    public function getColors() {
        $colors = [];
        foreach ($this->elements as $element) {
            $colors[] = $element->getColor();
        }
        return $colors;
    }

    public function y(Axis $axis) {
        $this->axes['y'] = $axis;
        return $this;
    }

    public function y2(Axis $axis) {
        $this->axes['y2'] = $axis;
        return $this;
    }

    public function x(Axis $axis) {
        $this->axes['x'] = $axis;
        return $this;
    }

    public function x2(Axis $axis) {
        $this->axes['x2'] = $axis;
        return $this;
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
}
