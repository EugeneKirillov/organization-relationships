<?php
namespace Owr\Test\Unit\Serializer;

use Owr\Collection\Relations;
use Owr\Entity\Organization\Edge;
use Owr\Entity\Organization\Factory;
use Owr\Entity\Organization\Node;
use Owr\Serializer\Context;
use Owr\Serializer\Serializer;
use Owr\Serializer\Strategy\ListDeserializeStrategy;
use Owr\Serializer\Strategy\ListSerializeStrategy;
use Owr\Serializer\Strategy\TreeSerializeStrategy;

//TODO: Split this test class to separate tests (serialize / deserialize)
class SerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test case 1: single root node without children
     *
     * @covers Serializer::deserialize
     */
    public function testDeserializeSingleNode()
    {
        $this->markTestSkipped('TODO: modify Serializer to support single-node hierarchy');

        $serializer = $this->getSerializer();

        // case 1: no children
        $relations = $serializer->deserialize(['org_name' => 'node 1']);

        // verify relations list
        $this->assertInstanceOf(Relations::class, $relations);
        $this->assertAttributeNotEmpty('relations', $relations);
        $this->assertAttributeCount(1, 'relations', $relations);
        $this->assertAttributeContainsOnly(Edge::class, 'relations', $relations);

        // verify edge structure
        $edge = $relations->getRelations()[0];
        $this->assertAttributeInstanceOf(Node::class, 'source', $edge);
        $this->assertEquals($edge->getSource(), $edge->getTarget());
        $this->assertAttributeEquals('self', 'relationType', $edge);

        // verify node structure
        $node = $edge->getSource();
        $this->assertAttributeEquals('node 1', 'name', $node);

        // case 1.1: empty child list
        $relations = $serializer->deserialize(['org_name' => 'node 1', 'daughters' => []]);

        $this->assertInstanceOf(Relations::class, $relations);
        $this->assertAttributeNotEmpty('relations', $relations);
        $this->assertAttributeCount(1, 'relations', $relations);
        $this->assertAttributeContainsOnly(Edge::class, 'relations', $relations);

        $edge = $relations->getRelations()[0];
        $this->assertAttributeInstanceOf(Node::class, 'source', $edge);
        $this->assertEquals($edge->getSource(), $edge->getTarget());
        $this->assertAttributeEquals('self', 'relationType', $edge);

        $node = $edge->getSource();
        $this->assertAttributeEquals('node 1', 'name', $node);
    }

    /**
     * Test case 2: root node with children
     *
     * @covers Serializer::deserialize
     */
    public function testDeserializeNodeWithChildren()
    {
        $relations = $this->getSerializer()->deserialize([
            'org_name' => 'node 1',
            'daughters' => [
                ['org_name' => 'node 1.1'],
                ['org_name' => 'node 1.2'],
                ['org_name' => 'node 1.3'],
            ]
        ]);

        // verify relations list
        $this->assertAttributeCount(12, 'relations', $relations);

        // verify child-parent edges structure
        $edge = $relations->getRelations()[0];
        $this->assertAttributeEquals('node 1', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.1', 'name', $edge->getTarget());
        $this->assertAttributeEquals('child', 'relationType', $edge);

        $edge = $relations->getRelations()[1];
        $this->assertAttributeEquals('node 1', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.2', 'name', $edge->getTarget());
        $this->assertAttributeEquals('child', 'relationType', $edge);

        $edge = $relations->getRelations()[2];
        $this->assertAttributeEquals('node 1', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.3', 'name', $edge->getTarget());
        $this->assertAttributeEquals('child', 'relationType', $edge);

        $edge = $relations->getRelations()[3];
        $this->assertAttributeEquals('node 1.1', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1', 'name', $edge->getTarget());
        $this->assertAttributeEquals('parent', 'relationType', $edge);

        $edge = $relations->getRelations()[4];
        $this->assertAttributeEquals('node 1.2', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1', 'name', $edge->getTarget());
        $this->assertAttributeEquals('parent', 'relationType', $edge);

        $edge = $relations->getRelations()[5];
        $this->assertAttributeEquals('node 1.3', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1', 'name', $edge->getTarget());
        $this->assertAttributeEquals('parent', 'relationType', $edge);

        // verify sibling edges structure
        $edge = $relations->getRelations()[6];
        $this->assertAttributeEquals('node 1.1', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.2', 'name', $edge->getTarget());
        $this->assertAttributeEquals('sibling', 'relationType', $edge);

        $edge = $relations->getRelations()[7];
        $this->assertAttributeEquals('node 1.1', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.3', 'name', $edge->getTarget());
        $this->assertAttributeEquals('sibling', 'relationType', $edge);

        $edge = $relations->getRelations()[8];
        $this->assertAttributeEquals('node 1.2', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.1', 'name', $edge->getTarget());
        $this->assertAttributeEquals('sibling', 'relationType', $edge);

        $edge = $relations->getRelations()[9];
        $this->assertAttributeEquals('node 1.2', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.3', 'name', $edge->getTarget());
        $this->assertAttributeEquals('sibling', 'relationType', $edge);

        $edge = $relations->getRelations()[10];
        $this->assertAttributeEquals('node 1.3', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.1', 'name', $edge->getTarget());
        $this->assertAttributeEquals('sibling', 'relationType', $edge);

        $edge = $relations->getRelations()[11];
        $this->assertAttributeEquals('node 1.3', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.2', 'name', $edge->getTarget());
        $this->assertAttributeEquals('sibling', 'relationType', $edge);
    }

    /**
     * Test case 3: node with children,
     * one of the children has nested child
     *
     * @covers Serializer::deserialize
     */
    public function testDeserializeNodeWithNestedChildren()
    {
        $relations = $this->getSerializer()->deserialize([
            'org_name' => 'node 1',
            'daughters' => [
                [
                    'org_name' => 'node 1.1',
                    'daughters' => [
                        [
                            'org_name' => 'node 1.1.1'
                        ],
                    ],
                ],
                ['org_name' => 'node 1.2'],
                ['org_name' => 'node 1.3'],
            ]
        ]);

        // verify relations list
        $this->assertAttributeCount(14, 'relations', $relations);

        // verify edges for nested children
        $edge = $relations->getRelations()[1];
        $this->assertAttributeEquals('node 1.1', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.1.1', 'name', $edge->getTarget());
        $this->assertAttributeEquals('child', 'relationType', $edge);

        $edge = $relations->getRelations()[5];
        $this->assertAttributeEquals('node 1.1.1', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.1', 'name', $edge->getTarget());
        $this->assertAttributeEquals('parent', 'relationType', $edge);
    }

    /**
     * Test case 4: node with children,
     * one of the children has nested child,
     * nested child has common parents:
     * - for the first parent nested child have own child node
     * - for the second parent nested child have only name property
     *
     * @covers Serializer::deserialize
     */
    public function testDeserializeNestedChildrenWithCommonParent()
    {
        $relations = $this->getSerializer()->deserialize([
            'org_name' => 'node 1',
            'daughters' => [
                [
                    'org_name' => 'node 1.1',
                    'daughters' => [
                        [
                            'org_name' => 'node 1.1.1',
                            'daughters' => [
                                [
                                    'org_name' => 'node 1.1.1.1'
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'org_name' => 'node 1.2',
                ],
                [
                    'org_name' => 'node 1.3',
                    'daughters' => [
                        [
                            'org_name' => 'node 1.1.1'
                        ],
                    ],
                ],
            ]
        ]);

        $this->assertAttributeCount(18, 'relations', $relations);

        // verify edges for nested children
        $edge = $relations->getRelations()[2];
        $this->assertAttributeEquals('node 1.1.1', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.1.1.1', 'name', $edge->getTarget());
        $this->assertAttributeEquals('child', 'relationType', $edge);

        $edge = $relations->getRelations()[5];
        $this->assertAttributeEquals('node 1.3', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.1.1', 'name', $edge->getTarget());
        $this->assertAttributeEquals('child', 'relationType', $edge);

        $edge = $relations->getRelations()[8];
        $this->assertAttributeEquals('node 1.1.1.1', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.1.1', 'name', $edge->getTarget());
        $this->assertAttributeEquals('parent', 'relationType', $edge);

        $edge = $relations->getRelations()[11];
        $this->assertAttributeEquals('node 1.1.1', 'name', $edge->getSource());
        $this->assertAttributeEquals('node 1.3', 'name', $edge->getTarget());
        $this->assertAttributeEquals('parent', 'relationType', $edge);
    }

    /**
     * Test case 1: single root node without children
     *
     * @covers Serializer::serialize
     */
    public function testSerializeSingleNodeAsTree()
    {
        $relations = new Relations;
        $relations->addRelation($this->getFactoryMock()->createRootEdge('node 1'));

        $relations = $this->getSerializer()->serialize($relations, Context::AS_TREE);
        $this->assertEquals(['org_id' => 1, 'org_name' => 'node 1'], $relations);
    }

    /**
     * Test case 2: root node with children
     *
     * @covers Serializer::serialize
     */
    public function testSerializeNodeWithChildren()
    {
        $factory = $this->getFactoryMock();

        $relations = new Relations;
        $relations->addRelation($factory->createChildEdge('node 1', 'node 1.1'));
        $relations->addRelation($factory->createParentEdge('node 1.1', 'node 1'));
        $relations->addRelation($factory->createChildEdge('node 1', 'node 1.2'));
        $relations->addRelation($factory->createParentEdge('node 1.2', 'node 1'));
        $relations->addRelation($factory->createChildEdge('node 1', 'node 1.3'));
        $relations->addRelation($factory->createParentEdge('node 1.3', 'node 1'));
        $relations->addRelation($factory->createSiblingEdge('node 1.1', 'node 1.2'));
        $relations->addRelation($factory->createSiblingEdge('node 1.1', 'node 1.3'));
        $relations->addRelation($factory->createSiblingEdge('node 1.2', 'node 1.1'));
        $relations->addRelation($factory->createSiblingEdge('node 1.2', 'node 1.3'));
        $relations->addRelation($factory->createSiblingEdge('node 1.3', 'node 1.1'));
        $relations->addRelation($factory->createSiblingEdge('node 1.3', 'node 1.2'));

        $relations = $this->getSerializer()->serialize($relations, Context::AS_TREE);
        $this->assertEquals([
            'org_id'    => 1,
            'org_name'  => 'node 1',
            'daughters' => [
                [
                    'org_id'   => 2,
                    'org_name' => 'node 1.1',
                ],
                [
                    'org_id'   => 5,
                    'org_name' => 'node 1.2',
                ],
                [
                    'org_id'   => 7,
                    'org_name' => 'node 1.3',
                ],
            ]
        ], $relations);
    }

    /**
     * Test case 3: node with children,
     * one of the children has nested child
     *
     * @covers Serializer::serialize
     */
    public function testSerializeNodeWithNestedChildren()
    {
        $factory = $this->getFactoryMock();

        $relations = new Relations;
        $relations->addRelation($factory->createChildEdge('node 1', 'node 1.1'));
        $relations->addRelation($factory->createParentEdge('node 1.1', 'node 1'));
        $relations->addRelation($factory->createChildEdge('node 1', 'node 1.2'));
        $relations->addRelation($factory->createParentEdge('node 1.2', 'node 1'));
        $relations->addRelation($factory->createChildEdge('node 1', 'node 1.3'));
        $relations->addRelation($factory->createParentEdge('node 1.3', 'node 1'));
        $relations->addRelation($factory->createSiblingEdge('node 1.1', 'node 1.2'));
        $relations->addRelation($factory->createSiblingEdge('node 1.1', 'node 1.3'));
        $relations->addRelation($factory->createSiblingEdge('node 1.2', 'node 1.1'));
        $relations->addRelation($factory->createSiblingEdge('node 1.2', 'node 1.3'));
        $relations->addRelation($factory->createSiblingEdge('node 1.3', 'node 1.1'));
        $relations->addRelation($factory->createSiblingEdge('node 1.3', 'node 1.2'));
        $relations->addRelation($factory->createChildEdge('node 1.1', 'node 1.1.1'));
        $relations->addRelation($factory->createParentEdge('node 1.1.1', 'node 1.1'));

        $relations = $this->getSerializer()->serialize($relations, Context::AS_TREE);
        $this->assertEquals([
            'org_id'    => 1,
            'org_name'  => 'node 1',
            'daughters' => [
                [
                    'org_id'    => 2,
                    'org_name'  => 'node 1.1',
                    'daughters' => [
                        [
                            'org_id'   => 3,
                            'org_name' => 'node 1.1.1',
                        ],
                    ],
                ],
                [
                    'org_id'   => 5,
                    'org_name' => 'node 1.2',
                ],
                [
                    'org_id'   => 7,
                    'org_name' => 'node 1.3',
                ],
            ]
        ], $relations);
    }

    /**
     * Test case 4: node with children,
     * one of the children has nested child,
     * nested child has common parents:
     * - for the first parent nested child have own child node
     * - for the second parent nested child have only name property
     *
     * @covers Serializer::serialize
     */
    public function testSerializeNestedChildrenWithCommonParent()
    {
        $factory = $this->getFactoryMock();

        $relations = new Relations;
        $relations->addRelation($factory->createChildEdge('node 1', 'node 1.1'));
        $relations->addRelation($factory->createParentEdge('node 1.1', 'node 1'));
        $relations->addRelation($factory->createChildEdge('node 1', 'node 1.2'));
        $relations->addRelation($factory->createParentEdge('node 1.2', 'node 1'));
        $relations->addRelation($factory->createChildEdge('node 1', 'node 1.3'));
        $relations->addRelation($factory->createParentEdge('node 1.3', 'node 1'));
        $relations->addRelation($factory->createSiblingEdge('node 1.1', 'node 1.2'));
        $relations->addRelation($factory->createSiblingEdge('node 1.1', 'node 1.3'));
        $relations->addRelation($factory->createSiblingEdge('node 1.2', 'node 1.1'));
        $relations->addRelation($factory->createSiblingEdge('node 1.2', 'node 1.3'));
        $relations->addRelation($factory->createSiblingEdge('node 1.3', 'node 1.1'));
        $relations->addRelation($factory->createSiblingEdge('node 1.3', 'node 1.2'));
        $relations->addRelation($factory->createChildEdge('node 1.1', 'node 1.1.1'));
        $relations->addRelation($factory->createParentEdge('node 1.1.1', 'node 1.1'));
        $relations->addRelation($factory->createChildEdge('node 1.1.1', 'node 1.1.1.1'));
        $relations->addRelation($factory->createParentEdge('node 1.1.1.1', 'node 1.1.1'));
        $relations->addRelation($factory->createChildEdge('node 1.3', 'node 1.1.1'));
        $relations->addRelation($factory->createParentEdge('node 1.1.1', 'node 1.3'));

        $relations = $this->getSerializer()->serialize($relations, Context::AS_TREE);
        $this->assertEquals([
            'org_id'    => 1,
            'org_name'  => 'node 1',
            'daughters' => [
                [
                    'org_id'    => 2,
                    'org_name'  => 'node 1.1',
                    'daughters' => [
                        [
                            'org_id'    => 3,
                            'org_name'  => 'node 1.1.1',
                            'daughters' => [
                                [
                                    'org_id'   => 4,
                                    'org_name' => 'node 1.1.1.1'
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'org_id'   => 5,
                    'org_name' => 'node 1.2',
                ],
                [
                    'org_id'    => 7,
                    'org_name'  => 'node 1.3',
                    'daughters' => [
                        [
                            'org_id'    => 3,
                            'org_name'  => 'node 1.1.1',
                            'daughters' => [
                                [
                                    'org_id'   => 4,
                                    'org_name' => 'node 1.1.1.1'
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ], $relations);
    }

    /**
     * @return Factory
     */
    private function getFactoryMock()
    {
        $nodes = [
            ['node 1',       new Node('node 1')],
            ['node 1.1',     new Node('node 1.1')],
            ['node 1.1.1',   new Node('node 1.1.1')],
            ['node 1.1.1.1', new Node('node 1.1.1.1')],
            ['node 1.2',     new Node('node 1.2')],
            ['node 1.2.1',   new Node('node 1.2.1')],
            ['node 1.3',     new Node('node 1.3')],
        ];

        foreach ($nodes as $i => $node) {
            \Closure::bind(function ($node) use ($i) { $node->id = $i + 1; }, null, $node[1])->__invoke($node[1]);
        }


        $factory = $this
            ->getMockBuilder(Factory::class)
            ->disableOriginalConstructor()
            ->setMethods(['createNode'])
            ->getMock()
        ;

        $factory
            ->expects($this->any())
            ->method('createNode')
            ->will($this->returnValueMap($nodes))
        ;

        return $factory;
    }

    /**
     * @return Serializer
     */
    private function getSerializer()
    {
        $listDeserializeStrategy = new ListDeserializeStrategy($this->getFactoryMock());
        $listSerializeStrategy   = new ListSerializeStrategy($this->getFactoryMock());
        $treeSerializeStrategy   = new TreeSerializeStrategy($this->getFactoryMock());

        $context = new Context(
            $listDeserializeStrategy,
            $listSerializeStrategy,
            $treeSerializeStrategy
        );

        $serializer = new Serializer($context);
        return $serializer;
    }
}
