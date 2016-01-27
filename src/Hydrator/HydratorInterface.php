<?php
namespace Owr\Hydrator;

/**
 * Interface to hydrate object with data fetched from DB
 * and extract data from object to map it to DB structure
 *
 * @package Owr\Hydrator
 */
interface HydratorInterface
{
    /**
     * Hydrate object instance with data
     *
     * @param array $data
     *
     * @return object
     */
    public function hydrate(array $data);

    /**
     * Extract data from the object
     *
     * @param object $object
     *
     * @return array
     */
    public function toArray($object);
}
