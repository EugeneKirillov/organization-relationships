<?php
namespace Owr\App\Controller\Api;
use Owr\Service\OrganizationsService;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class OrganizationsController
 *
 * @package Owr\App\Controller\Api
 */
class OrganizationsController
{
    /**
     * @var OrganizationsService
     */
    private $organizations;

    /**
     * @param OrganizationsService $organizations
     */
    public function __construct(OrganizationsService $organizations)
    {
        $this->organizations = $organizations;
    }

    /**
     * Get organization's relations action
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function getRelationsAction(Request $request, Response $response, $args)
    {
        return $response->withJson($this->organizations->getRelations($args['name']));
    }
}