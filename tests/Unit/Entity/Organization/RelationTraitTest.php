<?php
namespace Owr\Test\Unit\Entity\Organization;

use Owr\Entity;

class RelationTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testParents()
    {
        $relation = $this->getRelationObject();

        $this->assertEmpty($relation->getParents());

        $node = new Entity\Organization(100, 'test');
        $this->assertFalse($relation->hasParent($node));

        $relation->addParent($node);
        $this->assertTrue($relation->hasParent($node));

        $this->assertArrayHasKey(100, $relation->getParents());
    }

    public function testSiblings()
    {
        $relation = $this->getRelationObject();

        $this->assertEmpty($relation->getSiblings());

        $node = new Entity\Organization(100, 'test');
        $this->assertFalse($relation->hasSibling($node));

        $relation->addSibling($node);
        $this->assertTrue($relation->hasSibling($node));

        $this->assertArrayHasKey(100, $relation->getSiblings());
    }

    public function testChilds()
    {
        $relation = $this->getRelationObject();

        $this->assertEmpty($relation->getChilds());

        $node = new Entity\Organization(100, 'test');
        $this->assertFalse($relation->hasChild($node));

        $relation->addChild($node);
        $this->assertTrue($relation->hasChild($node));

        $this->assertArrayHasKey(100, $relation->getChilds());
    }

    /**
     * @return \Owr\Entity\Organization\RelationTrait
     */
    private function getRelationObject()
    {
        return $this->getObjectForTrait(Entity\Organization\RelationTrait::class);
    }
}
