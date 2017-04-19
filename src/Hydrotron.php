<?php

namespace mrkrstphr\Hydrotron;

use mrkrstphr\Instantiator\Instantiator;
use Peridot\Leo\ObjectPath\ObjectPath;
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
        $this->data = new ObjectPath($data);
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

        $value = $this->data->get($attr);

        if ($value) {
            $value = $value->getPropertyValue();
            $instance = $this->instantiator->instantiate($className, is_array($value) ? $value : []);
            $this->runCallbacks($instance, $callbacks);
        }
    }

    /**
     * @param $attr
     * @param array ...$callbacks
     */
    public function when($attr, ...$callbacks)
    {
        $value = $this->data->get($attr);

        if ($value) {
            $this->runCallbacks($value->getPropertyValue(), $callbacks);
        }
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
