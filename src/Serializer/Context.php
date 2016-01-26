<?php
namespace Owr\Serializer;

use Owr\Serializer\Strategy\DeserializeStrategyInterface;
use Owr\Serializer\Strategy\SerializeStrategyInterface;

class Context
{
    const AS_TREE = 'tree';
    const AS_LIST = 'list';

    /**
     * @var DeserializeStrategyInterface
     */
    private $listDeserializeStrategy;

    /**
     * @var SerializeStrategyInterface
     */
    private $listSerializeStrategy;

    /**
     * @var SerializeStrategyInterface
     */
    private $treeSerializeStrategy;

    /**
     * Context constructor
     *
     * @param DeserializeStrategyInterface $listDeserializeStrategy
     * @param SerializeStrategyInterface $listSerializeStrategy
     * @param SerializeStrategyInterface $treeSerializeStrategy
     */
    public function __construct(
        DeserializeStrategyInterface $listDeserializeStrategy,
        SerializeStrategyInterface $listSerializeStrategy,
        SerializeStrategyInterface $treeSerializeStrategy
    ) {
        // TODO: add strategies with registerSerializeStrategy / registerDeserializeStrategy
        $this->listDeserializeStrategy = $listDeserializeStrategy;
        $this->listSerializeStrategy   = $listSerializeStrategy;
        $this->treeSerializeStrategy   = $treeSerializeStrategy;
    }

    /**
     * Returns deserialize strategy
     *
     * @param string $context
     *
     * @return DeserializeStrategyInterface
     */
    public function getDeserializeStrategy($context = Context::AS_LIST)
    {
        return $this->listDeserializeStrategy;
    }

    /**
     * Returns serialize strategy
     *
     * @param string $context
     *
     * @return SerializeStrategyInterface
     */
    public function getSerializeStrategy($context = Context::AS_LIST)
    {
        switch ($context) {
            case static::AS_TREE:
                return $this->treeSerializeStrategy;
                break;

            case static::AS_LIST:
            default:
                return $this->listSerializeStrategy;
                break;
        }
    }


}
