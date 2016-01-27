<?php
namespace Owr\Hydrator;

abstract class AbstractHydrator implements HydratorInterface
{
    /**
     * Set private property to the object
     *
     * @param object $object
     * @param string $property
     * @param mixed $value
     *
     * @return $this
     */
    protected function setPrivateProperty($object, $property, $value)
    {
        \Closure::bind(function ($object, $value) use ($property) {
            $object->$property = $value;
        }, null, $object)->__invoke($object, $value);
        return $this;
    }
}
