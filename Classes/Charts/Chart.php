<?php


namespace con4gis\VisualizationBundle\Classes\Charts;

use con4gis\VisualizationBundle\Classes\Exceptions\InvalidChartTypeException;
use con4gis\VisualizationBundle\Classes\Source\Source;
use con4gis\VisualizationBundle\Resources\contao\models\ChartModel;

Class Chart
{
    protected $title = '';
    protected $enableAnimations = true;
    protected $enableExport = true;
    protected $theme = '';
    protected $colorSetName = '';
    protected $colors = [];
    protected $yAxis = [];
    protected $xAxis = [];

    protected $showLegend = false;
    protected $legendFontSize = 16;
    protected $legendInteractive = true;

    protected $elements = [];

    /**
     * @return string
     */
    public function createEncodableArray() {
        $array = [
            'colorSet' => [
                'name' => $this->colorSetName,
                'colors' => $this->colors
            ],
            'title' => $this->title,
            'animationEnabled' => $this->enableAnimations,
            'exportEnabled' => $this->enableExport,
            'theme' => $this->theme,
            'data' => $this->createEncodableDataArray()
            ];

        if ($this->showLegend === true) {
            $array['legend'] = [
                'cursor' => 'pointer',
                'fontsize' => $this->legendFontSize,
                'interactive' => $this->legendInteractive
            ];
        }

        if (!empty($this->yAxis) === true) {
            $array['axisY'] = $this->yAxis;
        }

        if (!empty($this->xAxis) === true) {
            $array['axisX'] = $this->xAxis;
        }

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

    public function legend(int $fontsize = 16, bool $interactive = true) {
        $this->showLegend = true;
        $this->legendFontSize = $fontsize;
        $this->legendInteractive = $interactive;
        return $this;
    }

    public function yAxis(string $title, bool $includeZero = true, string $suffix = '') {
        $this->yAxis['title'] = $title;
        $this->yAxis['includeZero'] = $includeZero;
        $this->yAxis['suffix'] = $suffix;
        return $this;
    }

    public function xAxis(string $title) {
        $this->xAxis['title'] = $title;
        return $this;
    }

    /**
     * @param string $title
     * @return Chart
     */
    public function setTitle(string $title): Chart
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param array $colors
     * @return Chart
     */
    public function setColors(string $name, array $colors): Chart
    {
        $this->colorSetName = $name;
        $this->colors = $colors;
        return $this;
    }

    /**
     * @param bool $enableAnimations
     * @return Chart
     */
    public function setEnableAnimations(bool $enableAnimations = true): Chart
    {
        $this->enableAnimations = $enableAnimations;
        return $this;
    }

    /**
     * @param bool $enableExport
     * @return Chart
     */
    public function setEnableExport(bool $enableExport = true): Chart
    {
        $this->enableExport = $enableExport;
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
