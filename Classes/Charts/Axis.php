<?php


namespace con4gis\VisualizationBundle\Classes\Charts;


class Axis
{
    protected $horizontal = true;
    protected $show = false;
    protected $type = 'indexed';
    protected $scale = 'linear';
    protected $rotate = 0;
    protected $label = [];
    protected $inverted = false;

    public function createEncodableArray() {
        $array = [];
        $array['show'] = $this->show;
        $array['type'] = $this->type;

        if ($this->horizontal === false) {
            $array['scale'] = $this->scale;
        }

        if ($this->rotate !== 0) {
            $array['label']['rotate'] = $this->rotate;
        }

        if (!empty($this->label)) {
            $array['label'] = $this->label;
        }

        if ($this->horizontal === false) {
            $array['inverted'] = $this->inverted;
        }

        return $array;
    }

    /**
     * @param bool $horizontal
     * @return Axis
     */
    public function setHorizontal(bool $horizontal): Axis
    {
        $this->horizontal = $horizontal;
        return $this;
    }

    /**
     * @param bool $show
     * @return Axis
     */
    public function setShow(bool $show): Axis
    {
        $this->show = $show;
        return $this;
    }

    /**
     * @param string $type
     * @return Axis
     */
    public function setType(string $type): Axis
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $scale
     * @return Axis
     */
    public function setScale(string $scale): Axis
    {
        $this->scale = $scale;
        return $this;
    }

    /**
     * @param int $rotate
     * @return Axis
     */
    public function setRotate(int $rotate): Axis
    {
        $this->rotate = $rotate;
        return $this;
    }

    /**
     * @param string $text
     * @param string $position
     * @return Axis
     */
    public function setLabel(string $text, string $position): Axis
    {
        $this->label = ['text' => $text, 'position' => $position];
        return $this;
    }

    /**
     * @param bool $inverted
     * @return Axis
     */
    public function setInverted(bool $inverted): Axis
    {
        $this->inverted = $inverted;
        return $this;
    }
}