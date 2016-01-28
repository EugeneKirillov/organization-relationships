<?php
namespace Owr\Repository;

use Doctrine\DBAL\Connection;
use Owr\Collection\Relations;
use Owr\Entity\Graph\EdgeInterface;
use Owr\Exception\DatabaseErrorException;
use Owr\Exception\NotFoundException;
use Owr\Hydrator\HydratorInterface;

/**
 * Class OrganizationsRepository
 *
 * @package Owr\Repository
 */
class OrganizationsRepository
{
    /**
     * @var Connection
     */
    private $dbal;

    /**
     * @var HydratorInterface
     */
    private $entityHydrator;

    /**
     * @var HydratorInterface
     */
    private $collectionHydrator;

    /**
     * OrganizationsRepository constructor
     *
     * @param Connection $connection
     * @param HydratorInterface $entityHydrator
     * @param HydratorInterface $collectionHydrator
     */
    public function __construct(
        Connection $connection,
        HydratorInterface $entityHydrator,
        HydratorInterface $collectionHydrator
    ) {
        $this->dbal               = $connection;
        $this->entityHydrator     = $entityHydrator;
        $this->collectionHydrator = $collectionHydrator;
    }

    /**
     * Finds relations collection for node by it's name
     *
     * @param string $name
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return Relations
     *
     * @throws DatabaseErrorException
     * @throws NotFoundException
     */
    public function findRelations($name, $limit = null, $offset = null)
    {
        try {
            $relations = $this->dbal->fetchAll(
                'SELECT '
                . 'o2.id AS `source.id`, o2.name as `source.name`, '
                . 'o.id AS `target.id`, o.name AS `target.name`, '
                . 'r.type '
                . 'FROM organizations o '
                . 'INNER JOIN relations r ON o.id = r.target '
                . 'INNER JOIN organizations o2 ON o2.id = r.source '
                . 'WHERE o2.name = :name '
                . 'ORDER BY o.name ASC '
                . 'LIMIT :offset, :limit',
                [
                    'name'   => $name,
                    'offset' => (int) $offset,
                    'limit'  => (int) $limit,
                ],
                [
                    'name'   => \PDO::PARAM_STR,
                    'offset' => \PDO::PARAM_INT,
                    'limit'  => \PDO::PARAM_INT,
                ]
            );

            if (empty($relations)) {
                throw new NotFoundException(sprintf(NotFoundException::RELATIONS_MESSAGE, $name));
            }

            $relations = array_map(function ($edge) {
                return $this->entityHydrator->hydrate([
                    'source' => [
                        'id'   => $edge['source.id'],
                        'name' => $edge['source.name'],
                    ],
                    'target' => [
                        'id'   => $edge['target.id'],
                        'name' => $edge['target.name'],
                    ],
                    'type' => $edge['type'],
                ]);
            }, $relations);

            return $this->collectionHydrator->hydrate($relations);

        } catch (\Doctrine\DBAL\DBALException $exception) {
            throw new DatabaseErrorException($exception);
        }
    }

    /**
     * Saves Relations collection
     *
     * @param Relations $relations
     *
     * @return Relations
     *
     * @throws DatabaseErrorException
     */
    public function save(Relations $relations)
    {
        try {
            $this->dbal->setAutoCommit(false);
            $this->dbal->beginTransaction();

            $relations = array_map(function (EdgeInterface $edge) {
                $edge = $this->entityHydrator->toArray($edge);

                $edge['source'] = $this->saveNode($edge['source']);
                $edge['target'] = $this->saveNode($edge['target']);
                $edge = $this->saveEdge($edge);

                return $this->entityHydrator->hydrate($edge);
            }, $this->collectionHydrator->toArray($relations));

            $this->dbal->commit();
            $this->dbal->setAutoCommit(true);

            return $this->collectionHydrator->hydrate($relations);

        } catch (\Doctrine\DBAL\ConnectionException $exception) {
            throw new DatabaseErrorException($exception);

        } catch (\Doctrine\DBAL\DBALException $exception) {
            $this->dbal->rollBack();
            $this->dbal->setAutoCommit(true);

            throw new DatabaseErrorException($exception);
        }
    }

    /**
     * Saves Edge data
     *
     * @param array $edge Edge object converted to array
     *
     * @return array
     */
    private function saveEdge(array $edge)
    {
        $this->dbal->executeUpdate(
            'INSERT INTO `relations` (`source`, `target`, `type`) VALUES (:source, :target, :type) '
            . 'ON DUPLICATE KEY UPDATE `type` = :type',
            [
                'source' => $edge['source']['id'],
                'target' => $edge['target']['id'],
                'type'   => $edge['type'],
            ],
            [
                'source' => \PDO::PARAM_INT,
                'target' => \PDO::PARAM_INT,
                'type'   => \PDO::PARAM_STR,
            ]
        );

        return $edge;
    }

    /**
     * Saves Node data
     *
     * @param array $node Node object converted to array
     *
     * @return array
     */
    private function saveNode(array $node)
    {
        if (isset($node['id'])) {
            return $node;
        }

        $this->dbal->insert(
            'organizations',
            ['name' => $node['name']],
            ['name' => \PDO::PARAM_STR]
        );

        $node['id'] = $this->dbal->lastInsertId('organizations');
        return $node;
    }

    /**
     * Deletes all nodes with edges from database
     *
     * @throws DatabaseErrorException
     */
    public function delete()
    {
        try {
            $this->dbal->setAutoCommit(false);
            $this->dbal->beginTransaction();
            $this->dbal->executeQuery('DELETE FROM relations');
            $this->dbal->executeQuery('DELETE FROM organizations');
            $this->dbal->commit();
            $this->dbal->setAutoCommit(true);

        } catch (\Doctrine\DBAL\ConnectionException $exception) {
            throw new DatabaseErrorException($exception);

        } catch (\Doctrine\DBAL\DBALException $exception) {
            $this->dbal->rollBack();
            $this->dbal->setAutoCommit(true);

            throw new DatabaseErrorException($exception);
        }
    }
}
