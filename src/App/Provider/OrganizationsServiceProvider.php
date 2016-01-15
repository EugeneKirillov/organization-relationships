<?php
namespace Owr\App\Provider;

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
        $container['organizations'] = function ($container) {
            return new OrganizationsService();
        };
    }
}