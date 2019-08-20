<?php


namespace con4gis\VisualizationBundle\Classes\Charts;


class Axis
{
    protected $title = '';
    protected $includeZero = true;
    protected $suffix = '';
    protected $logarithmic = false;
    protected $logarithmBase = 2;
    protected $interval = 0;
    protected $reversed = true;
    protected $tickThickness = 0;
    protected $labelAngle = 0;

    public function createEncodableAxisArray() {
        $array = [];

        if ($this->title !== '') {
            $array['title'] = $this->title;
        }
        $array['includeZero'] = $this->includeZero;
        if ($this->suffix !== '') {
            $array['suffix'] = $this->suffix;
        }
        if ($this->logarithmic === true) {
            $array['logarithmic'] = $this->logarithmic;
            $array['logarithmBase'] = $this->logarithmBase;
        }
        if ($this->interval === true) {
            $array['interval'] = $this->interval;
        }
        if ($this->reversed === true) {
            $array['reversed'] = $this->reversed;
        }
        if ($this->tickThickness > 0) {
            $array['tickThickness'] = $this->tickThickness;
        }
        if ($this->labelAngle) {
            $array['labelAngle'] = $this->labelAngle;
        }
        return $array;
    }

    /**
     * @param string $title
     * @return Axis
     */
    public function setTitle(string $title): Axis
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param bool $includeZero
     * @return Axis
     */
    public function setIncludeZero(bool $includeZero): Axis
    {
        $this->includeZero = $includeZero;
        return $this;
    }

    /**
     * @param string $suffix
     * @return Axis
     */
    public function setSuffix(string $suffix): Axis
    {
        $this->suffix = $suffix;
        return $this;
    }

    /**
     * @param bool $logarithmic
     * @return Axis
     */
    public function setLogarithmic(bool $logarithmic): Axis
    {
        $this->logarithmic = $logarithmic;
        return $this;
    }

    /**
     * @param int $logarithmBase
     * @return Axis
     */
    public function setLogarithmBase(int $logarithmBase): Axis
    {
        $this->logarithmBase = $logarithmBase;
        return $this;
    }

    /**
     * @param int $interval
     * @return Axis
     */
    public function setInterval(int $interval): Axis
    {
        $this->interval = $interval;
        return $this;
    }

    /**
     * @param bool $reversed
     * @return Axis
     */
    public function setReversed(bool $reversed): Axis
    {
        $this->reversed = $reversed;
        return $this;
    }

    /**
     * @param int $tickThickness
     * @return Axis
     */
    public function setTickThickness(int $tickThickness): Axis
    {
        $this->tickThickness = $tickThickness;
        return $this;
    }

    /**
     * @param int $labelAngle
     * @return Axis
     */
    public function setLabelAngle(int $labelAngle): Axis
    {
        $this->labelAngle = $labelAngle;
        return $this;
    }
}