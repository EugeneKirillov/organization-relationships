<?php
namespace Owr\Serializer\Strategy;

/**
 * Interface DeserializeStrategyInterface
 *
 * @package Owr\Serializer\Strategy
 */
interface DeserializeStrategyInterface
{
    /**
     * Deserialize data to an object
     *
     * @param array $data
     *
     * @return mixed
     */
    public function deserialize(array $data);
}
