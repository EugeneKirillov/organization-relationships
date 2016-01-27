<?php
namespace Owr\Hydrator;

use Owr\Entity\Organization\Factory;

class EntityHydrator extends AbstractHydrator
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * Hydrator constructor
     *
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @inheritDoc
     */
    public function hydrate(array $data)
    {
        $source = $this->factory->createNode($data['source']['name']);
        $this->setPrivateProperty($source, 'id', $data['source']['id']);

        $target = $this->factory->createNode($data['target']['name']);
        $this->setPrivateProperty($target, 'id', $data['target']['id']);

        $edge = $this->factory->createEdge($source, $target, $data['type']);
        return $edge;
    }

    /**
     * @inheritDoc
     */
    public function toArray($edge)
    {
        return [
            'source' => [
                'id'   => $edge->getSource()->getId(),
                'name' => $edge->getSource()->getName(),
            ],
            'target' => [
                'id'   => $edge->getTarget()->getId(),
                'name' => $edge->getTarget()->getName(),
            ],
            'type'   => $edge->getRelationType(),
        ];
    }
}
