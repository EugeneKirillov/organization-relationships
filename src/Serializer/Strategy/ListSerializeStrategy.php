<?php
namespace Owr\Serializer\Strategy;

use Owr\Collection\Relations;
use Owr\Entity\Graph\EdgeInterface;

/**
 * Class ListSerializeStrategy
 *
 * @package Owr\Serializer\Strategy
 */
class ListSerializeStrategy implements SerializeStrategyInterface
{
    /**
     * Serialize relations collection as a list of relations
     *
     * @param Relations $object
     *
     * @return array
     */
    public function serialize($object)
    {
        return array_map(function(EdgeInterface $edge) {
            return $this->raw($edge);
        }, $object->getRelations());
    }

    /**
     * Converts Edge instance to an array
     *
     * @param EdgeInterface $edge
     *
     * @return array
     */
    private function raw(EdgeInterface $edge)
    {
        $type = $edge->getRelationType();
        switch ($type) {
            case EdgeInterface::RELATION_TYPE_PARENT:
                $type = 'parent';
                break;

            case EdgeInterface::RELATION_TYPE_SIBLING:
                $type = 'sister';
                break;

            case EdgeInterface::RELATION_TYPE_CHILD:
                $type = 'daughter';
                break;
        }

        return [
            'relationship_type' => (string) $type,
            'org_name'          => (string) $edge->getTarget()->getName(),
        ];
    }
}
