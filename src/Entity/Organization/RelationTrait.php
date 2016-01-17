<?php
namespace Owr\Entity\Organization;

use Owr\Entity\Organization;

/**
 * Trait to handle organization relations
 *
 * If represent organization relationships as a graph,
 * this trait will be responsible for handling edges
 * of the relations graph; the Organization class
 * will be responsible for handling nodes of the graph
 *
 * @package Owr\Entity
 */
trait RelationTrait
{
    /**
     * @var Organization[] List of parent nodes
     */
    private $parents = [];

    /**
     * @var Organization[] List of sibling nodes
     */
    private $siblings = [];

    /**
     * @var Organization[] List of children nodes
     */
    private $childs = [];

    /**
     * Returns list of parent organizations
     *
     * @return Organization[]
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Check if current organization has specified node as a parent
     *
     * @param Organization $node
     *
     * @return bool
     */
    public function hasParent(Organization $node)
    {
        return isset($this->parents[$node->getId()]);
    }

    /**
     * Add specified node as a parent
     *
     * @param Organization $node
     *
     * @return $this
     */
    public function addParent(Organization $node)
    {
        $this->parents[$node->getId()] = $node;
        return $this;
    }

    /**
     * Returns list of sibling organizations
     *
     * @return Organization[]
     */
    public function getSiblings()
    {
        return $this->siblings;
    }

    /**
     * Check if current organization has specified node as a sibling
     *
     * @param Organization $node
     *
     * @return bool
     */
    public function hasSibling(Organization $node)
    {
        return isset($this->siblings[$node->getId()]);
    }

    /**
     * Add specified node as a sibling
     *
     * @param Organization $node
     *
     * @return $this
     */
    public function addSibling(Organization $node)
    {
        $this->siblings[$node->getId()] = $node;
        return $this;
    }

    /**
     * Returns list of child organizations
     *
     * @return Organization[]
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Check if current organization has specified node as a child
     *
     * @param Organization $node
     *
     * @return bool
     */
    public function hasChild(Organization $node)
    {
        return isset($this->childs[$node->getId()]);
    }

    /**
     * Add specified node as a child node
     *
     * @param Organization $node
     *
     * @return $this
     */
    public function addChild(Organization $node)
    {
        $this->childs[$node->getId()] = $node;
        return $this;
    }
}
