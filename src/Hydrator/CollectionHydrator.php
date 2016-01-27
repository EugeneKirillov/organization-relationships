<?php
namespace Owr\Hydrator;

use Owr\Collection\Relations;

/**
 * Class CollectionHydrator
 *
 * @package Owr\Hydrator
 */
class CollectionHydrator extends AbstractHydrator
{
    /**
     * @inheritDoc
     */
    public function hydrate(array $data)
    {
        return new Relations($data);
    }

    /**
     * @inheritDoc
     */
    public function toArray($collection)
    {
        return $collection->getRelations();
    }
}
