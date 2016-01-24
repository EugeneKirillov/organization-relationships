<?php
namespace Owr\Test\Unit\Entity\Organization;

use Owr\Entity\Graph\EdgeInterface;
use Owr\Entity\Organization\Edge;
use Owr\Entity\Organization\Node;
use Owr\Exception\InvalidArgumentException;

class EdgeTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $source = new Node('source');
        $target = new Node('target');
        $type   = EdgeInterface::RELATION_TYPE_CHILD;
        $edge   = new Edge($source, $target, $type);

        $this->assertAttributeEquals($source, 'source', $edge);
        $this->assertAttributeEquals($target, 'target', $edge);
        $this->assertAttributeEquals($type, 'relationType', $edge);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Value 'daughter' is not supported as a relation type
     */
    public function testConstructInvalidRelationType()
    {
        $edge = new Edge(new Node('source'), new Node('target'), 'daughter');
    }

    public function testGetSource()
    {
        $edge = new Edge(new Node('source'), new Node('target'), EdgeInterface::RELATION_TYPE_CHILD);
        $this->assertAttributeEquals($edge->getSource(), 'source', $edge);
    }


    public function testGetTarget()
    {
        $edge = new Edge(new Node('source'), new Node('target'), EdgeInterface::RELATION_TYPE_CHILD);
        $this->assertAttributeEquals($edge->getTarget(), 'target', $edge);
    }

    public function testGetRelationType()
    {
        $edge = new Edge(new Node('source'), new Node('target'), EdgeInterface::RELATION_TYPE_CHILD);
        $this->assertAttributeEquals($edge->getRelationType(), 'relationType', $edge);
    }
}
