<?php
namespace Owr\Service;

/**
 * Class OrganizationsService
 *
 * @package Owr\Service
 */
class OrganizationsService
{
    /**
     * Returns list relations by organization's name
     *
     * @param string $name
     * @param int $page
     * @param int $count
     *
     * @return array
     */
    public function getRelations($name, $page = 1, $count = 10)
    {
        return array_slice([
            [
                "relationship_type"=> "self",
                "org_name" => $name,
            ],
            [
                "relationship_type"=> "parent",
                "org_name" => "Banana tree",
            ],
            [
                "relationship_type" => "parent",
                "org_name" => "Big banana tree",
            ],
            [
                "relationship_type" => "sister",
                "org_name" => "Brown Banana",
            ],
            [
                "relationship_type" => "sister",
                "org_name" => "Green Banana",
            ],
            [
                "relationship_type" => "daughter",
                "org_name" => "Phoneutria Spider",
            ],
            [
                "relationship_type" => "sister",
                "org_name" => "Yellow Banana",
            ],
        ], ($page - 1) * $count, $count);
    }
}
