<?php
namespace Owr\App;

use Owr\App\Controller\Api\OrganizationsController;
use Owr\App\Provider\OrganizationsServiceProvider;
use Slim\App as Application;

/**
 * Application class
 *
 * @package Owr\App
 */
class App extends Application
{
    /**
     * @param array|\Interop\Container\ContainerInterface $config
     * @param $debug
     */
    public function __construct($config, $debug)
    {
        $config['displayErrorDetails'] = $debug;

        parent::__construct(['settings' => $config]);
    }

    /**
     * Register application services
     *
     * @return $this
     */
    public function registerServices()
    {
        $this->getContainer()->register(new OrganizationsServiceProvider());

        return $this;
    }

    /**
     * Register application controllers
     *
     * @return $this
     */
    public function registerControllers()
    {
        $this->getContainer()['api_relations_controller'] = function ($container) {
            return new OrganizationsController($container['organizations']);
        };

        return $this;
    }

    /**
     * Register application controllers
     *
     * @return $this
     */
    public function registerRoutes()
    {
        // group API routes to /api/{version} prefix
        $this->group('/api/{version}', function () {

            // endpoint for fetching relations by organization's name
            $this->get('/organizations/relations/{name}', 'api_relations_controller:getRelationsAction');

            // endpoint to save organizations relations
            $this->post('/organizations/relations', 'api_relations_controller:createRelationsAction');

            // endpoint to delete all relations
            $this->delete('/organizations/relations', 'api_relations_controller:deleteRelationsAction');

        });

        // group web routes here (if needed)
        // group mobile routes here (if needed)

        return $this;
    }
}
