<?php

namespace mrkrstphr\Hydrotron;

use mrkrstphr\Instantiator\Instantiator;
use RuntimeException;

/**
 * Class Hydrotron
 * @package mrkrstphr\HydrotronHydrotron
 */
class Hydrotron
{
    /**
     * @var array
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
        $this->data = $data;
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

        if (array_key_exists($attr, $this->data)) {
            $instance = $this->instantiator->instantiate($className, $this->data[$attr]);
            $this->runCallbacks($instance, $callbacks);
        }
    }

    /**
     * @param $attr
     * @return $this
     */
    public function when($attr)
    {
        $callbacks = func_get_args();
        array_shift($callbacks);

        if (array_key_exists($attr, $this->data)) {
            $this->runCallbacks($this->data[$attr], $callbacks);
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
