<?php
namespace Owr\Entity\Graph;

/**
 * Trait to handle node's relations
 *
 * @package Owr\Entity\Graph
 */
trait RelationAwareTrait
{
    /**
     * @var EdgeInterface[]
     */
    private $relations = [];

    /**
     * Set whole list of relations
     *
     * @param EdgeInterface[] $relations
     *
     * @return $this
     */
    public function setRelations(array $relations)
    {
        $this->relations = $relations;
        return $this;
    }

    /**
     * Add new relations
     *
     * @param EdgeInterface $edge
     *
     * @return $this
     */
    public function addRelation(EdgeInterface $edge)
    {
        $this->relations[] = $edge;
        return $this;
    }

    /**
     * Returns list of existing relations
     *
     * @return EdgeInterface[]
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * Returns root node
     *
     * @return NodeInterface
     */
    public function getRoot()
    {
        // NB: This method expects the only cas when edge(s)
        // for root node added at first. If we can't garantee,
        // that root node's edge will be added as a first edge,
        // than this method should refactored

        // TODO: scan entire relations list to find root node -
        // node, that have only 'child' relations, is the root
        return $this->relations[0]->getSource();
    }

    /**
     * Returns list of parent nodes for node $node
     *
     * @param NodeInterface $node
     *
     * @return NodeInterface[]
     */
    public function getParents(NodeInterface $node)
    {
        return $this->filter($node, EdgeInterface::RELATION_TYPE_PARENT);
    }

    /**
     * Returns list of sibling nodes for node $node
     *
     * @param NodeInterface $node
     *
     * @return NodeInterface[]
     */
    public function getSiblings(NodeInterface $node)
    {
        return $this->filter($node, EdgeInterface::RELATION_TYPE_SIBLING);
    }

    /**
     * Returns list of child nodes for node $node
     *
     * @param NodeInterface $node
     *
     * @return NodeInterface[]
     */
    public function getChilds(NodeInterface $node)
    {
        return $this->filter($node, EdgeInterface::RELATION_TYPE_CHILD);
    }

    /**
     * Filters entire list of edges to match
     * edges that related to node $node by relation type $type
     * and extract target nodes from these edges
     *
     * @param NodeInterface $node
     * @param string $type
     *
     * @return NodeInterface[]
     */
    private function filter(NodeInterface $node, $type)
    {
        return array_reduce(array_filter($this->relations, function (EdgeInterface $edge) use ($node, $type) {
            return $edge->getRelationType() == $type && $edge->getSource()->equals($node);
        }), function (array $list, EdgeInterface $edge) {
            return array_merge($list, [$edge->getTarget()]);
        }, []);
    }
}
