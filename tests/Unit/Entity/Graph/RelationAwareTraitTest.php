<?php
namespace Owr\Test\Unit\Entity\Hierarchy;

use Owr\Entity\Graph\EdgeInterface;
use Owr\Entity\Graph\RelationAwareTrait;
use Owr\Entity\Organization\Edge;
use Owr\Entity\Organization\Node;

class RelationAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testAddRelation()
    {
        $hierarchy = $this->getHierarchy();

        $relations = $this->getRelationAwareTrait();
        foreach ($hierarchy as $edge) {
            $relations->addRelation($edge);
        }

        $this->assertAttributeCount(10, 'relations', $relations);
        $this->assertAttributeEquals($hierarchy, 'relations', $relations);
    }

    public function testGetRelations()
    {
        $hierarchy = $this->getHierarchy();

        $relations = $this->getRelationAwareTrait();
        foreach ($hierarchy as $edge) {
            $relations->addRelation($edge);
        }

        $this->assertAttributeEquals($relations->getRelations(), 'relations', $relations);
    }

    public function testGetRoot()
    {
        $hierarchy = $this->getHierarchy();

        $relations = $this->getRelationAwareTrait();
        foreach ($hierarchy as $edge) {
            $relations->addRelation($edge);
        }

        $this->assertEquals($hierarchy[0]->getSource(), $relations->getRootNode());
    }

    public function testGetParent()
    {
        $hierarchy = $this->getHierarchy();

        $relations = $this->getRelationAwareTrait();
        foreach ($hierarchy as $edge) {
            $relations->addRelation($edge);
        }

        // list of nodes ['node 1.1', 'node 1.2']
        $expected = [
            $hierarchy[1]->getSource(),
            $hierarchy[3]->getSource(),
        ];
        // parent nodes for node 'node 1.1.1'
        $actual = $relations->getParents($hierarchy[7]->getSource());

        $this->assertCount(2, $actual);
        $this->assertEquals($expected, $actual);
    }

    public function testGetSiblings()
    {
        $hierarchy = $this->getHierarchy();

        $relations = $this->getRelationAwareTrait();
        foreach ($hierarchy as $edge) {
            $relations->addRelation($edge);
        }

        // list of nodes ['node 1.2']
        $expected = [$hierarchy[3]->getSource()];
        // sibling nodes for node 'node 1.1'
        $actual = $relations->getSiblings($hierarchy[1]->getSource());

        $this->assertCount(1, $actual);
        $this->assertEquals($expected, $actual);
    }

    public function testGetChilds()
    {
        $hierarchy = $this->getHierarchy();

        $relations = $this->getRelationAwareTrait();
        foreach ($hierarchy as $edge) {
            $relations->addRelation($edge);
        }

        // list of nodes ['node 1.1.1']
        $expected = [$hierarchy[7]->getSource()];
        // child nodes for node 'node 1.2'
        $actual = $relations->getChilds($hierarchy[3]->getSource());

        $this->assertCount(1, $actual);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return RelationAwareTrait
     */
    private function getRelationAwareTrait()
    {
        return $this->getObjectForTrait(RelationAwareTrait::class);
    }

    /**
     * @return EdgeInterface[]
     */
    private function getHierarchy()
    {
        $nodes = [
            new Node('node 1'),
            new Node('node 1.1'),
            new Node('node 1.2'),
            new Node('node 1.1.1'),
        ];

        return [
            new Edge($nodes[0], $nodes[1], EdgeInterface::RELATION_TYPE_CHILD),
            new Edge($nodes[1], $nodes[0], EdgeInterface::RELATION_TYPE_PARENT),
            new Edge($nodes[0], $nodes[2], EdgeInterface::RELATION_TYPE_CHILD),
            new Edge($nodes[2], $nodes[0], EdgeInterface::RELATION_TYPE_PARENT),
            new Edge($nodes[1], $nodes[2], EdgeInterface::RELATION_TYPE_SIBLING),
            new Edge($nodes[2], $nodes[1], EdgeInterface::RELATION_TYPE_SIBLING),
            new Edge($nodes[1], $nodes[3], EdgeInterface::RELATION_TYPE_CHILD),
            new Edge($nodes[3], $nodes[1], EdgeInterface::RELATION_TYPE_PARENT),
            new Edge($nodes[2], $nodes[3], EdgeInterface::RELATION_TYPE_CHILD),
            new Edge($nodes[3], $nodes[2], EdgeInterface::RELATION_TYPE_PARENT),
        ];
    }
}
