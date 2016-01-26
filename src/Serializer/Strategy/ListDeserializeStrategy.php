<?php
namespace Owr\Serializer\Strategy;

use Owr\Collection\Relations;
use Owr\Entity\Graph\EdgeInterface;
use Owr\Entity\Graph\NodeInterface;

class ListDeserializeStrategy extends AbstractDeserializeStrategy
{
    /**
     * Deserialize data to relations collection
     *
     * @param array $data
     *
     * @return Relations
     */
    public function deserialize(array $data)
    {
        $childs   = $this->deserializeChilds($data);
        $parents  = $this->deserializeParents($childs);
        $siblings = $this->deserializeSiblings($childs);

        return new Relations(array_merge($childs, $parents, $siblings));
    }

    /**
     * Deserialize child edges
     *
     * @param array $data
     *
     * @return EdgeInterface[]
     */
    private function deserializeChilds(array $data)
    {
        $relations = [];

        $source  = $this->factory->createNode($this->getNodeName($data));
        $targets = $this->getNodeChilds($data);

        foreach ($targets as $data) {
            $target = $this->factory->createNode($this->getNodeName($data));

            $relations[] = $this->factory->createChildEdge($source, $target);
            $relations   = array_merge($relations, $this->deserializeChilds($data));
        }

        return $relations;
    }

    /**
     * Deserialize parent edges
     *
     * Parent edge is a child edge with an opposite direction.
     * To get the list of parent edges, source and target nodes
     * need to be swapped
     *
     * @param EdgeInterface[] $relations
     *
     * @return EdgeInterface[]
     */
    private function deserializeParents(array $relations)
    {
        $parents = [];

        foreach ($relations as $relation) {
            $parents[] = $this->factory->createParentEdge($relation->getTarget(), $relation->getSource());
        }

        return $parents;
    }

    /**
     * Deserialize sibling edges
     *
     * Goes through list of child edges (#1 foreach) and finds
     * for each source node list of nodes that have the same
     * parent (#2 foreach).
     *
     * TODO: add an self-described example
     *
     * @param EdgeInterface[] $relations
     *
     * @return EdgeInterface[]
     */
    private function deserializeSiblings(array $relations)
    {
        $siblings = [];

        foreach ($relations as $target) {
            foreach ($relations as $source) {

                // skip the same edges
                if (
                    $target->getSource()->equals($source->getSource())
                    && $target->getTarget()->equals($source->getTarget())
                ) {
                    continue;
                }

                // skip the edge which source's node equals to target (children of target node)
                if ($target->getTarget()->equals($source->getSource())) {
                    continue;
                }

                // skip the edge that doesn't have the same source node (the same parent)
                if (!$target->getSource()->equals($source->getSource())) {
                    continue;
                }

                $siblings[] = $this->factory->createSiblingEdge($target->getTarget(), $source->getTarget());
            }
        }

        return $siblings;
    }
}
