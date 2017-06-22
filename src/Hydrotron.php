<?php

namespace mrkrstphr\Hydrotron;

use mrkrstphr\Instantiator\Instantiator;
use Peridot\ObjectPath\ObjectPath;
use Peridot\ObjectPath\ObjectPathValue;
use RuntimeException;

/**
 * Class Hydrotron
 * @package mrkrstphr\HydrotronHydrotron
 */
class Hydrotron
{
    /**
     * @var ObjectPath
     */
    public $data;

    /**
     * @var Instantiator
     */
    private $instantiator;

    /**
     * Hydrotron constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = new ObjectPath($data);
    }

    /**
     * @param string $attr
     * @param string $className
     */
    public function instantiateWhen($attr, $className)
    {
        $callbacks = func_get_args();
        array_shift($callbacks);
        array_shift($callbacks);

        if (!$this->instantiator) {
            $this->instantiator = new Instantiator();
        }

        $value = $this->data->get($attr);

        if ($value) {
            $value = $value->getPropertyValue();
            $instance = $this->instantiator->instantiate($className, is_array($value) ? $value : []);
            $this->runCallbacks($instance, $callbacks);
        }
    }

    /**
     * @deprecated Used whenKeyExists() or whenNotEmpty()
     * @param string $attr
     * @return $this
     */
    public function when($attr)
    {
        return $this->whenNotEmpty($attr);
    }

    /**
     * Fires the passed callbacks when the passed $attr is a defined key and is not empty.
     *
     * @param string $attr
     * @return $this
     */
    public function whenNotEmpty($attr)
    {
        $callbacks = func_get_args();
        array_shift($callbacks);

        $value = $this->data->get($attr);

        if ($value && $value->getPropertyValue()) {
            $this->runCallbacks($value->getPropertyValue(), $callbacks);
        }

        return $this;
    }

    /**
     * Fires the passed callbacks when the passed $attr is a defined key in the data array.
     *
     * @param string $attr
     * @return $this
     */
    public function whenKeyExists($attr)
    {
        $callbacks = func_get_args();
        array_shift($callbacks);

        $value = $this->data->get($attr);

        if ($value instanceof ObjectPathValue) {
            $this->runCallbacks($value->getPropertyValue(), $callbacks);
        }

        return $this;
    }

    /**
     * @param string $value
     * @param array $callbacks
     */
    protected function runCallbacks($value, array $callbacks)
    {
        foreach ($callbacks as $index => $callback) {
            if (is_callable($callback)) {
                $value = $callback($value);
            } else {
                throw new RuntimeException('Invalid callback supplied at index ' . $index);
            }
        }
    }
}
