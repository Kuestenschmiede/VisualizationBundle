<?php


namespace con4gis\VisualizationBundle\Classes\Source;

use con4gis\VisualizationBundle\Classes\Exceptions\InvalidSourceTypeException;
use Contao\Model;
use Contao\Model\Collection;

class SourceList extends Source implements \Iterator
{
    protected $data;
    private $index = 0;

    /**
     * Source constructor.
     * @param $data
     * @throws InvalidSourceTypeException
     */
    public function __construct($data)
    {
        if ($data instanceof Collection) {
            foreach ($data as $model) {
                $this->data[] = new Source($model);
            }
        } elseif (is_array($data)) {
            foreach ($data as $array) {
                $this->data[] = Source::create($array);
            }
        } else {
            throw new InvalidSourceTypeException();
        }
    }

    public function current()
    {
        return $this->data[$this->index];
    }

    public function next()
    {
        $this->index += 1;
        if ($this->data[$this->index] instanceof Source === false) {
            $this->index = 0;
        }
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return ($this->data[$this->index] instanceof Source);
    }

    public function rewind()
    {
        $this->index = 0;
    }


}