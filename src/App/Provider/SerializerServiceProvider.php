<?php
namespace Owr\App\Provider;

use Owr\Serializer\Context;
use Owr\Serializer\Serializer;
use Owr\Serializer\Strategy\ListDeserializeStrategy;
use Owr\Serializer\Strategy\ListSerializeStrategy;
use Owr\Serializer\Strategy\TreeSerializeStrategy;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class SerializerServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $container)
    {
        $container['serializer.context'] = function ($container) {
            $listDeserializeStrategy = new ListDeserializeStrategy($container['organizations.factory']);
            $listSerializeStrategy   = new ListSerializeStrategy($container['organizations.factory']);
            $treeSerializeStrategy   = new TreeSerializeStrategy($container['organizations.factory']);

            return new Context(
                $listDeserializeStrategy,
                $listSerializeStrategy,
                $treeSerializeStrategy
            );
        };

        $container['serializer'] = function ($container) {
            return new Serializer($container['serializer.context']);
        };
    }
}
