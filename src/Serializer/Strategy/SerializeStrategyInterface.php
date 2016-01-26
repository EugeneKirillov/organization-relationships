<?php
namespace Owr\Serializer\Strategy;

/**
 * Interface SerializeStrategyInterface
 *
 * @package Owr\Serializer\Strategy
 */
interface SerializeStrategyInterface
{
    /**
     * Serialize an object to an array
     *
     * @param object $object
     *
     * @return array
     */
    public function serialize($object);
}
