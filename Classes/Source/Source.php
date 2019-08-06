<?php


namespace con4gis\VisualizationBundle\Classes\Source;

use con4gis\VisualizationBundle\Classes\Exceptions\InvalidSourceTypeException;
use Contao\Model;
use Contao\Model\Collection;

class Source
{
    protected $data;

    /**
     * Source constructor.
     * @param $data
     * @throws InvalidSourceTypeException
     */
    public function __construct($data)
    {
        if ($data instanceof Model) {
            $this->data = $data->row();
        } elseif (is_array($data)) {
            foreach ($data as $value) {
                // Do not allow multi-dimensional arrays
                if ((is_array($value) === true) || (is_object($value) === true)) {
                    throw new InvalidSourceTypeException();
                }
            }
            $this->data = $data;
        } else {
            throw new InvalidSourceTypeException();
        }
    }

    /**
     * @param $data
     * @return Source
     * @throws InvalidSourceTypeException
     */
    public static function create($data) {
        if ($data instanceof Model) {
            return new self($data);
        } elseif ($data instanceof Collection) {
            return new SourceList($data);
        } elseif (is_array($data)) {
            if (is_array(current($data)) === true) {
                return new SourceList($data);
            } else {
                return new Source($data);
            }
        } else {
            throw new InvalidSourceTypeException();
        }
    }

    public function get($index) {
        return $this->data[$index] ? $this->data[$index] : null;
    }


}