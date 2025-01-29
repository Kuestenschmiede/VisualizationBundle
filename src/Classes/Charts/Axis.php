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
    protected $scale = 'linear';
    protected $rotate = 0;
    protected $labelText = '';
    protected $labelPosition = 0;
    protected $labelCount = 0;
    protected $inverted = false;
    protected $ticks = [];
    protected $ticksAll = [];
    protected $tickFormattedValue = [];
    protected $tickFormattedValueAll = [];
    protected $tickRotate = [];
    protected $tickFormat = "";
    private ?int $min = null;
    private ?int $max = null;

    public function createEncodableArray()
    {
        $array = [];
        $array['show'] = $this->show;

        if ($this->horizontal === false) {
            $array['scale'] = $this->scale;
        }

        if ($this->rotate !== 0) {
            $array['label']['rotate'] = $this->rotate;
        }

        $array['debug'] = ['labelText' => $this->labelText, 'labelPosition' => $this->labelPosition];
        if (($this->labelText !== '') && ($this->labelPosition !== 0)) {
            if ($this->horizontal === true) {
                switch ($this->labelPosition) {
                    case 1:
                        $position = static::LABEL_POSITION_INNER_RIGHT;

                        break;
                    case 2:
                        $position = static::LABEL_POSITION_INNER_CENTER;

                        break;
                    case 3:
                        $position = static::LABEL_POSITION_INNER_LEFT;

                        break;
                    case 4:
                        $position = static::LABEL_POSITION_OUTER_RIGHT;

                        break;
                    case 5:
                        $position = static::LABEL_POSITION_OUTER_CENTER;

                        break;
                    case 6:
                        $position = static::LABEL_POSITION_OUTER_LEFT;

                        break;
                    default:
                        $position = static::LABEL_POSITION_INNER_RIGHT;

                        break;
                }
            } else {
                switch ($this->labelPosition) {
                    case 1:
                        $position = static::LABEL_POSITION_INNER_UP;

                        break;
                    case 2:
                        $position = static::LABEL_POSITION_INNER_MIDDLE;

                        break;
                    case 3:
                        $position = static::LABEL_POSITION_INNER_DOWN;

                        break;
                    case 4:
                        $position = static::LABEL_POSITION_OUTER_UP;

                        break;
                    case 5:
                        $position = static::LABEL_POSITION_OUTER_MIDDLE;

                        break;
                    case 6:
                        $position = static::LABEL_POSITION_OUTER_DOWN;

                        break;
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

        if (!empty($this->ticks) === true) {
            $array['tick']['values'] = $this->ticks;
            $array['tick']['singleValues'] = $this->ticks;
            $array['tick']['format'] = $this->tickFormattedValue;
            $array['tick']['singleFormat'] = $this->tickFormattedValue;
            $array['tick']['rotate'] = $this->rotate;
        }
        $array['tickFormat'] = $this->tickFormat;
        $array['labelCount'] = $this->labelCount;
        if ($this->min !== null) {
            $array['min'] = $this->min;
        }
        if ($this->max !== null) {
            $array['max'] = $this->max;
        }
        if (!empty($this->ticksAll) === true) {
            $array['tick']['valuesAll'] = $this->ticksAll;
            $array['tick']['formatAll'] = $this->tickFormattedValueAll;
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
        $this->labelText = $text;
        $this->labelPosition = $position;

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

    public function setTickValue(int $value, string $formattedValue, int $rotate = 0)
    {
        if (in_array($value, $this->ticks) === false) {
            $this->ticks[] = $value;
            $this->tickFormattedValue[$value] = $formattedValue;
            $this->rotate = $rotate;
        }
    }
    
    /**
     * @param string $tickFormat
     */
    public function setTickFormat(string $tickFormat): void
    {
        $this->tickFormat = $tickFormat;
    }
    
    /**
     * @return string
     */
    public function getTickFormat(): string
    {
        return $this->tickFormat;
    }
    
    /**
     * @return int
     */
    public function getLabelCount(): int
    {
        return $this->labelCount;
    }
    
    /**
     * @param int $labelCount
     */
    public function setLabelCount(int $labelCount): void
    {
        $this->labelCount = $labelCount;
    }

    /**
     * @return int
     */
    public function getMin(): int
    {
        return $this->min;
    }

    /**
     * @param int $min
     */
    public function setMin(int $min): void
    {
        $this->min = $min;
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @param int $max
     */
    public function setMax(int $max): void
    {
        $this->max = $max;
    }

    /**
     * @return array
     */
    public function getTicks(): array
    {
        return $this->ticks;
    }
    public function setTickValueAll(int $value, string $formattedValue)
    {
        if (in_array($value, $this->ticksAll) === false) {
            $this->ticksAll[] = $value;
            $this->tickFormattedValueAll[$value] = $formattedValue;
        }
    }
}
