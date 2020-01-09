<?php

namespace con4gis\VisualizationBundle\Classes\Charts;

class CoordinateSystem
{
    protected $rotated = false;
    protected $axes;

    public function __construct(Axis $x, Axis $y, Axis $y2)
    {
        $this->axes['x'] = $x;
        $this->axes['y'] = $y->setHorizontal(false);
        $this->axes['y2'] = $y2->setHorizontal(false);
    }

    public function createEncodableArray()
    {
        $array = [];
        $array['rotated'] = $this->rotated;

        $array['x'] = $this->axes['x']->createEncodableArray();
        $array['y'] = $this->axes['y']->createEncodableArray();

        if (isset($this->axes['y2']) === true) {
            $array['y2'] = $this->axes['y2']->createEncodableArray();
        }

        return $array;
    }

    /**
     * @param bool $rotated
     * @return CoordinateSystem
     */
    public function setRotated(bool $rotated): CoordinateSystem
    {
        $this->rotated = $rotated;

        return $this;
    }

    public function x() : Axis
    {
        return $this->axes['x'];
    }

    public function y() : Axis
    {
        return $this->axes['y'];
    }

    public function y2() : Axis
    {
        return $this->axes['y2'];
    }
}
