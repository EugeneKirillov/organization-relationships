<?php
namespace Owr\Serializer\Strategy;

use Owr\Entity\Organization\Factory;

abstract class AbstractDeserializeStrategy implements DeserializeStrategyInterface
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * Serializer constructor
     *
     * @param $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Returns node's name
     *
     * @param array $data
     *
     * @return string
     */
    public function getNodeName(array $data)
    {
        return filter_var(trim($data['org_name']), FILTER_SANITIZE_STRING);
    }

    /**
     * Returns list of child nodes
     *
     * @param array $data
     *
     * @return array
     */
    public function getNodeChilds(array $data)
    {
        return isset($data['daughters']) ? $data['daughters'] : [];
    }
}