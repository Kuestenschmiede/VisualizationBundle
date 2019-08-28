<?php


namespace con4gis\VisualizationBundle\Classes\Charts;


class Axis
{
    const TYPE_INDEXED = 'indexed';
    const TYPE_CATEGORY = 'category';
    const TYPE_TIME_SERIES = 'timeseries';

    const LABEL_POSITION_INNER_RIGHT = 'inner-right';
    const LABEL_POSITION_INNER_CENTER = 'inner-center';
    const LABEL_POSITION_INNER_LEFT = 'inner-left';
    const LABEL_POSITION_INNER_UP = 'inner-top';
    const LABEL_POSITION_INNER_MIDDLE = 'inner-middle';
    const LABEL_POSITION_INNER_DOWN = 'inner-bottom';

    const LABEL_POSITION_OUTER_RIGHT = 'outer-right';
    const LABEL_POSITION_OUTER_CENTER = 'outer-center';
    const LABEL_POSITION_OUTER_LEFT = 'outer-left';
    const LABEL_POSITION_OUTER_UP = 'outer-top';
    const LABEL_POSITION_OUTER_MIDDLE = 'outer-middle';
    const LABEL_POSITION_OUTER_DOWN = 'outer-bottom';

    protected $horizontal = true;
    protected $show = false;
    protected $type = 'indexed';
    protected $scale = 'linear';
    protected $rotate = 0;
    protected $labelText = '';
    protected $labelPosition = 0;
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

        if (($this->labelText !== '') && ($this->labelPosition !== 0)) {

            if ($this->horizontal === true) {
                switch ($this->labelPosition) {
                    case 1:
                        $position = static::LABEL_POSITION_INNER_RIGHT;
                        breaK;
                    case 2:
                        $position = static::LABEL_POSITION_INNER_CENTER;
                        breaK;
                    case 3:
                        $position = static::LABEL_POSITION_INNER_LEFT;
                        breaK;
                    case 4:
                        $position = static::LABEL_POSITION_OUTER_RIGHT;
                        breaK;
                    case 5:
                        $position = static::LABEL_POSITION_OUTER_CENTER;
                        breaK;
                    case 6:
                        $position = static::LABEL_POSITION_OUTER_LEFT;
                        breaK;
                    default:
                        $position = static::LABEL_POSITION_INNER_RIGHT;
                        break;
                }
            } else {
                switch ($this->labelPosition) {
                    case 1:
                        $position = static::LABEL_POSITION_INNER_UP;
                        breaK;
                    case 2:
                        $position = static::LABEL_POSITION_INNER_MIDDLE;
                        breaK;
                    case 3:
                        $position = static::LABEL_POSITION_INNER_DOWN;
                        breaK;
                    case 4:
                        $position = static::LABEL_POSITION_OUTER_UP;
                        breaK;
                    case 5:
                        $position = static::LABEL_POSITION_OUTER_MIDDLE;
                        breaK;
                    case 6:
                        $position = static::LABEL_POSITION_OUTER_DOWN;
                        breaK;
                    default:
                        $position = static::LABEL_POSITION_INNER_UP;
                        break;
                }
            }

            $array['label'] = ['text' => $this->labelText, 'position' => $position];
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
    public function setHorizontal(bool $horizontal = true): Axis
    {
        $this->horizontal = $horizontal;
        return $this;
    }

    /**
     * @param bool $show
     * @return Axis
     */
    public function setShow(bool $show = true): Axis
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
     * @param int $position
     * @return Axis
     */
    public function setLabel(string $text, int $position): Axis
    {
        $this->labelText =  $text;
        $this->labelPosition =  $position;
        return $this;
    }

    /**
     * @param bool $inverted
     * @return Axis
     */
    public function setInverted(bool $inverted = true): Axis
    {
        $this->inverted = $inverted;
        return $this;
    }
}