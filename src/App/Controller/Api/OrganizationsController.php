<?php
namespace Owr\App\Controller\Api;

use Owr\App\Controller\PaginationTrait;
use Owr\Serializer\Context;
use Owr\Serializer\Serializer;
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
     * @var Serializer
     */
    private $serializer;

    /**
     * @var OrganizationsService
     */
    private $service;

    /**
     * @param Serializer $serializer
     * @param OrganizationsService $service
     */
    public function __construct(Serializer $serializer, OrganizationsService $service)
    {
        $this->service = $service;
        $this->serializer = $serializer;
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

        $relations = $this->service->getRelations($name, $page, $count);
        $relations = $this->serializer->serialize($relations);

        return $response->withJson($relations);
    }

    /**
     * Save organizations relations action
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function createRelationsAction(Request $request, Response $response, $args)
    {
        // TODO: add validation for content type
        // TODO: add validation for data structure
        $relations = $request->getParsedBody();

        $relations = $this->serializer->deserialize($relations);
        $relations = $this->service->saveRelations($relations);
        $relations = $this->serializer->serialize($relations, Context::AS_TREE);

        return $response->withJson($relations, 201);
    }

    /**
     * Delete organizations relations action
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function deleteRelationsAction(Request $request, Response $response, $args)
    {
        $this->service->removeOrganizations();
        return $response->withJson(null, 204);
    }
}
