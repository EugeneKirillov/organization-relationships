<?php
namespace Owr\Collection;

use Owr\Entity\Graph\EdgeInterface;
use Owr\Entity\Graph\RelationAwareTrait;

/**
 * Class Relations
 *
 * @package Owr\Collection
 */
class Relations
{
    use RelationAwareTrait;

    /**
     * Relations constructor
     *
     * @param EdgeInterface[] $relations
     */
    public function __construct(array $relations = [])
    {
        $this->relations = $relations;
    }

    /**
     * Returns filtered relations collections
     *
     * Filters current EdgeInterface collections with $callback function
     *
     * @param callable $callback
     *
     * @return static
     */
    public function filter($callback)
    {
        return new static(array_values(array_filter($this->relations, $callback)));
    }
}
