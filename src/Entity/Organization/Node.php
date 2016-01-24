<?php
namespace Owr\Entity\Organization;

use Owr\Entity\Graph\NodeInterface;

/**
 * Node class
 *
 * @package Owr\Entity\Organization
 */
class Node implements NodeInterface
{
    /**
     * @var int Node's unique identifier
     */
    private $id;

    /**
     * @var string Node's name
     */
    private $name;

    /**
     * Node constructor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Returns node's ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns node's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function equals(NodeInterface $node)
    {
        if (!$node instanceof static) {
            return false;
        }

        /** @var Node $node */
        return $this->name == $node->getName();
    }
}
