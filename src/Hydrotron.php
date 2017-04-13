<?php

namespace mrkrstphr\Hydrotron;

use mrkrstphr\Instantiator\Instantiator;

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
     * @param array ...$callbacks
     */
    public function instantiateWhen($attr, $className, ...$callbacks)
    {
        if (!$this->instantiator) {
            $this->instantiator = new Instantiator();
        }

        if (array_key_exists($attr, $this->data)) {
            $instance = $this->instantiator->instantiate($className, $this->data);
            $this->runCallbacks($instance, $callbacks);
        }
    }

    /**
     * @param $attr
     * @param array ...$callbacks
     */
    public function when($attr, ...$callbacks)
    {
        if (array_key_exists($attr, $this->data)) {
            $this->runCallbacks($this->data[$attr], $callbacks);
        }
    }

    /**
     * @param string $value
     * @param array $callbacks
     */
    protected function runCallbacks($value, array $callbacks)
    {
        foreach ($callbacks as $callback) {
            $value = $callback($value);
        }
    }
}
