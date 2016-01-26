<?php
namespace Owr\Serializer;

use Owr\Collection\Relations;

/**
 * Class Serializer
 *
 * @package Owr\Serializer
 */
class Serializer
{
    /**
     * @var Context
     */
    private $context;

    /**
     * Serializer constructor
     *
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * Serialize relations collection
     *
     * @param Relations $relations
     * @param string $context
     *
     * @return array
     */
    public function serialize(Relations $relations, $context = Context::AS_LIST)
    {
        return $this->context->getSerializeStrategy($context)->serialize($relations);
    }

    /**
     * Deserialize data to relations collection
     *
     * @param array $data
     * @param string $context
     *
     * @return Relations
     */
    public function deserialize(array $data, $context = Context::AS_LIST)
    {
        return $this->context->getDeserializeStrategy($context)->deserialize($data);
    }
}
