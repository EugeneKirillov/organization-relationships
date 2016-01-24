<?php
namespace Owr\Entity\Organization;

use Owr\Entity\Graph\EdgeInterface;
use Owr\Entity\Graph\NodeInterface;

/**
 * Class OrganizationFactory
 *
 * @package Owr\Entity\Organization
 */
class Factory
{
    /**
     * @var array internal cache
     */
    private $storage = [];

    /**
     * Creates new instance of Organization\Node class
     *
     * If instance with specified name already exists - returns
     * this instance from internal cache, otherwise generates
     * new instance and stores it in the cache.
     *
     * @param string $name
     *
     * @return Node
     */
    public function createNode($name)
    {
        $hash = md5($name);

        if (!isset($this->storage[$hash])) {
            $this->storage[$hash] = new Node($name);
        }

        return $this->storage[$hash];
    }

    /**
     * Creates new instance of Organization\Edge class
     *
     * Builds new Edge instance based on passed parameters
     *
     * @param NodeInterface|string $source
     * @param NodeInterface|string $target
     * @param string $type
     *
     * @return Edge
     */
    public function createEdge($source, $target, $type)
    {
        $source = (!$source instanceof NodeInterface)
            ? $this->createNode($source)
            : $source;

        $target = (!$target instanceof NodeInterface)
            ? $this->createNode($target)
            : $target;

        return new Edge($source, $target, $type);
    }

    /**
     * Creates new instance of Edge for parent relation type
     *
     * This type of Edge's relations used to describe
     * that $target node is a parent for $source node
     *
     * @param NodeInterface|string $source
     * @param NodeInterface|string $target
     *
     * @return Edge
     */
    public function createParentEdge($source, $target)
    {
        return $this->createEdge($source, $target, EdgeInterface::RELATION_TYPE_PARENT);
    }

    /**
     * Creates new instance of Edge for sibling relation type
     *
     * This type of Edge's relations used to describe
     * that $target node and $source node are siblings
     *
     * @param NodeInterface|string $source
     * @param NodeInterface|string $target
     *
     * @return Edge
     */
    public function createSiblingEdge($source, $target)
    {
        return $this->createEdge($source, $target, EdgeInterface::RELATION_TYPE_SIBLING);
    }

    /**
     * Creates new instance of Edge for child relation type
     *
     * This type of Edge's relations used to describe
     * that $source node is a parent for $target node
     *
     * @param NodeInterface|string $source
     * @param NodeInterface|string $target
     *
     * @return Edge
     */
    public function createChildEdge($source, $target)
    {
        return $this->createEdge($source, $target, EdgeInterface::RELATION_TYPE_CHILD);
    }

    /**
     * Creates a root instance of Edge class
     *
     * This type of Edge class used when there is only one node
     * it the whole hierarchy
     *
     * @param NodeInterface|string $source
     *
     * @return Edge
     */
    public function createRootEdge($source)
    {
        return $this->createEdge($source, $source, EdgeInterface::RELATION_TYPE_SELF);
    }
}
