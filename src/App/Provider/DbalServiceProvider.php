<?php
namespace Owr\App\Provider;

use Doctrine\DBAL\DriverManager;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class DbalServiceProvider
 *
 * @package Owr\App\Provider
 */
class DbalServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $container)
    {
        $container['dbal'] = function ($container) {
            return DriverManager::getConnection($container['db.options']);
        };
    }
}
