<?php
namespace Owr\App;

/**
 * Class AppFactory
 *
 * @package Owr\App
 */
final class AppFactory
{
    /**
     * Create Application's instance
     *
     * @param array $config
     * @param bool $debug
     *
     * @return App
     */
    public static function createApp($config, $debug = false)
    {
        return (new App($config, $debug))
            ->registerServices()
            ->registerControllers()
            ->registerRoutes();
    }

    /**
     * Create Application's instance
     *
     * @param array $config
     * @param bool $debug
     *
     * @return App
     */
    public static function createTestApp($config, $debug = true)
    {
        return (new App($config, $debug))
            ->registerServices()
            ->registerControllers()
            ->registerRoutes();
    }
}
