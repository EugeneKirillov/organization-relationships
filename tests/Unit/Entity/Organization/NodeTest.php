<?php
namespace Owr\Test\Unit\Entity\Organization;


use Owr\Entity\Graph\NodeInterface;
use Owr\Entity\Organization\Node;

class NodeTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $node = new Node('node 1');
        $this->assertAttributeEquals('node 1', 'name', $node);
    }

    public function testGetId()
    {
        $node = new Node('node 1');
        // set private property 'id'
        \Closure::bind(function ($node) { $node->id = 100; }, null, $node)->__invoke($node);
        $this->assertAttributeEquals($node->getId(), 'id', $node);
    }

    public function testGetName()
    {
        $node = new Node('node 1');
        $this->assertAttributeEquals($node->getName(), 'name', $node);
    }

    public function testEquals()
    {
        $node1 = new Node('node 1');
        $this->assertTrue($node1->equals($node1));

        $node2 = new Node('node 1');
        $this->assertTrue($node1->equals($node2));

        $node3 = new Node('node 3');
        $this->assertFalse($node1->equals($node3));

        $nodeX = $this->getMock(NodeInterface::class);
        $this->assertFalse($node1->equals($nodeX));
    }
}
