<?php
namespace Owr\Serializer\Strategy;

use Owr\Collection\Relations;
use Owr\Entity\Graph\EdgeInterface;
use Owr\Entity\Graph\NodeInterface;

/**
 * Class TreeSerializeStrategy
 *
 * @package Owr\Serializer\Strategy
 */
class TreeSerializeStrategy implements SerializeStrategyInterface
{
    /**
     * Serialize relations collection as a tree of nodes
     *
     * @param Relations $object
     *
     * @return array
     */
    public function serialize($object)
    {
        $root   = $object->getRootNode();
        $childs = $object->filter(function (EdgeInterface $edge) {
            return $edge->getRelationType() === EdgeInterface::RELATION_TYPE_CHILD;
        });

        return $this->serializeNode($root, $childs);
    }

    /**
     * Serialize Node instances to an array
     *
     * @param NodeInterface $root Root node
     * @param Relations $relations List of child edges
     *
     * @return array
     */
    private function serializeNode(NodeInterface $root, Relations $relations)
    {
        // get list of child edges
        $childs = $relations->filter(function (EdgeInterface $edge) use ($root) {
            return $edge->getSource()->equals($root);
        });

        // get list of serialized child nodes
        $childs = array_map(function (EdgeInterface $edge) use ($relations) {
            return $this->serializeNode($edge->getTarget(), $relations);
        }, $childs->getRelations());

        return $this->raw($root, $childs);
    }

    /**
     * Converts Node instance to an array
     *
     * @param NodeInterface $node Node to serialize
     * @param array $childs List of serialized child nodes
     *
     * @return array
     */
    private function raw(NodeInterface $node, array $childs)
    {
        $data = [
            'org_id'    => $node->getId(),
            'org_name'  => (string) $node->getName(),
            'daughters' => $childs,
        ];

        if (!isset($data['org_id'])) {
            unset($data['org_id']);
        }

        if (empty($data['daughters'])) {
            unset($data['daughters']);
        }

        return $data;
    }
}
