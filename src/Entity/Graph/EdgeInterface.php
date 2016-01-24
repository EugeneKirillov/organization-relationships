<?php
namespace Owr\Entity\Graph;

/**
 * Interface EdgeInterface
 *
 * If represent nodes relationships as a graph,
 * this interface will be responsible for handling edges
 * of the graph; the NodeInterface instance will be
 * responsible for handling nodes of the graph
 *
 * @package Owr\Entity\Graph
 */
interface EdgeInterface
{
    const RELATION_TYPE_PARENT  = 'parent';
    const RELATION_TYPE_SIBLING = 'sibling';
    const RELATION_TYPE_CHILD   = 'child';
    const RELATION_TYPE_SELF    = 'self';

    /**
     * Returns source node
     *
     * @return NodeInterface
     */
    public function getSource();

    /**
     * Returns target node
     *
     * @return NodeInterface
     */
    public function getTarget();

    /**
     * Returns relationship between source and target nodes
     *
     * @return string
     */
    public function getRelationType();
}
