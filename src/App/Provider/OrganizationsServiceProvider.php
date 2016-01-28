<?php
namespace Owr\App\Provider;

use Owr\Entity\Organization\Factory;
use Owr\Entity\Strategy\Md5HashGeneratorStrategy;
use Owr\Hydrator\CollectionHydrator;
use Owr\Hydrator\GraphHydrator;
use Owr\Hydrator\EntityHydrator;
use Owr\Repository\OrganizationsRepository;
use Owr\Service\OrganizationsService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class OrganizationsServiceProvider
 *
 * @package Owr\App\Provider
 */
class OrganizationsServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $container)
    {
        $container['organizations.repository'] = function ($container) {
            return new OrganizationsRepository(
                $container['dbal'],
                new EntityHydrator($container['organizations.factory']),
                new CollectionHydrator()
            );
        };

        $container['organizations'] = function ($container) {
            return new OrganizationsService(
                $container['organizations.repository']
            );
        };
    }
}
