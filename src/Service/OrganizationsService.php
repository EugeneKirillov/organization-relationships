<?php
namespace Owr\Service;

use Owr\Collection\Relations;
use Owr\Repository\OrganizationsRepository;

/**
 * Class OrganizationsService
 *
 * @package Owr\Service
 */
class OrganizationsService implements OrganizationsInterface
{
    /**
     * @var OrganizationsRepository
     */
    protected $repository;

    /**
     * OrganizationsService constructor
     *
     * @param OrganizationsRepository $repository
     */
    public function __construct(OrganizationsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Returns list relations by organization's name
     *
     * @param string $name
     * @param int $page
     * @param int $count
     *
     * @return Relations
     */
    public function getRelations($name, $page = 1, $count = 10)
    {
        return $this->repository->findRelations($name, $count, ($page - 1) * $count);
    }

    /**
     * Add new organization hierarchy with relations
     *
     * @param Relations $relations
     *
     * @return Relations
     */
    public function saveRelations(Relations $relations)
    {
        // TODO: handle case when $relations consist only of one node
        return $this->repository->save($relations);
    }

    /**
     * Removes all organizations with its relations
     *
     * @return void
     */
    public function removeOrganizations()
    {
        $this->repository->delete();
    }
}
