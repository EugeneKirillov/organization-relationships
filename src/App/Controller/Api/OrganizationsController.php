<?php
namespace Owr\App\Controller\Api;

use Owr\App\Controller\PaginationTrait;
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
    use PaginationTrait;

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
        $name  = $args['name'];
        $page  = $this->getPage($request->getParam('page', 1));
        $count = $this->getCountPerPage($request->getParam('count', 10));

        return $response->withJson($this->organizations->getRelations($name, $page, $count));
    }
}
