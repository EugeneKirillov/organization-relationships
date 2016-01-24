<?php
namespace Owr\Entity\Organization;

use Owr\Entity\Graph\EdgeInterface;
use Owr\Entity\Graph\NodeInterface;
use Owr\Exception\InvalidArgumentException;

/**
 * Edge class
 *
 * @package Owr\Entity\Organization
 */
class Edge implements EdgeInterface
{
    /**
     * @var NodeInterface Source node
     */
    private $source;

    /**
     * @var NodeInterface Target node
     */
    private $target;

    /**
     * @var string Relation between source and target node
     */
    private $relationType;

    /**
     * Edge constructor
     *
     * @param NodeInterface $source
     * @param NodeInterface $target
     * @param string $relationType
     *
     */
    public function __construct(NodeInterface $source, NodeInterface $target, $relationType)
    {
        $this->source = $source;
        $this->target = $target;

        if (!in_array($relationType, [
            EdgeInterface::RELATION_TYPE_SELF,
            EdgeInterface::RELATION_TYPE_CHILD,
            EdgeInterface::RELATION_TYPE_PARENT,
            EdgeInterface::RELATION_TYPE_SIBLING,
        ])) {
            throw new InvalidArgumentException("Value '$relationType' is not supported as a relation type");
        }
        $this->relationType = $relationType;
    }

    /**
     * Returns source node
     *
     * @return NodeInterface
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Returns target node
     *
     * @return NodeInterface
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Returns relationship between source and target nodes
     *
     * @return string
     */
    public function getRelationType()
    {
        return $this->relationType;
    }
}
