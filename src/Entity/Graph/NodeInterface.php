<?php
namespace Owr\Entity\Graph;

/**
 * Interface NodeInterface
 *
 * If represent nodes relationships as a graph,
 * this interface will be responsible for handling
 * nodes of the graph; the EdgeInterface instance
 * will be responsible for handling edges of the graph
 *
 * @package Owr\Entity\Graph
 */
interface NodeInterface
{
    /**
     * Checks if current node equals to $node
     *
     * @param NodeInterface $node
     * @return bool
     */
    public function equals(NodeInterface $node);
}
