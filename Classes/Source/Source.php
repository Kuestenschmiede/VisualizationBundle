<?php


namespace con4gis\VisualizationBundle\Classes\Source;

use con4gis\VisualizationBundle\Classes\Exceptions\InvalidSourceTypeException;
use Contao\Model;
use Contao\Model\Collection;

class Source implements \Iterator
{
    protected $entries;
    private $current = 0;

    /**
     * Source constructor.
     * @param $data
     * @throws InvalidSourceTypeException
     */
    public function __construct($data)
    {
        $this->addData($data);
    }

    public function addData($data) {
        if ($data instanceof Collection) {
            foreach ($data as $model) {
                $this->entries[] = new Entry($model->row());
            }
        } elseif ($data instanceof Model) {
            $this->entries[] = new Entry($data->row());
        } elseif (is_array($data)) {
            $depth = $this->getArrayDepth($data);
            switch ($depth) {
                case 1:
                    $this->entries[] = new Entry($data);
                    breaK;
                case 2:
                    foreach ($data as $arr) {
                        $this->entries[] = new Entry($arr);
                    }
                    break;
                default:
                    throw new InvalidSourceTypeException();
                    break;
            }
        } else {
            throw new InvalidSourceTypeException();
        }
    }

    public function get($index) {
        return $this->entries[$this->current]->get($index);
    }

    public function combine(Source $source) {
        foreach ($source->entries as $entry) {
            $this->entries[] = $entry;
        }
        return $this;
    }

    private function getArrayDepth(array $array) {
        $depth = 1;
        foreach($array as $value) {
            if (is_array($value) === true) {
                $depth += $this->getArrayDepth($value);
                break;
            }
        }
        return $depth;
    }

    public function current()
    {
        return $this->entries[$this->current];
    }

    public function next()
    {
        $this->current += 1;
    }

    public function key()
    {
        return $this->current;
    }

    public function valid()
    {
        return (!empty($this->entries[$this->current]) === true);
    }

    public function rewind()
    {
        $this->current = 0;
    }
}