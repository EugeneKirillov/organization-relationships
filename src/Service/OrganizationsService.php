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

    /**
     * Add new organization hierarchy with relations
     *
     * @param array $relations
     *
     * @return array
     */
    public function createRelations($relations)
    {
        return [
            "org_name"  => "Paradise Island",
            "daughters" => [
                [
                    "org_name"  => "Banana tree",
                    "daughters" => [
                        [
                            "org_name" => "Yellow Banana"
                        ],
                        [
                            "org_name" => "Brown Banana"
                        ],
                        [
                            "org_name" => "Black Banana",
                            "daughters" => [
                                ["org_name" => "Phoneutria Spider"]
                            ],
                        ],
                    ],
                ],
                [
                    "org_name" => "Big banana tree",
                    "daughters" => [
                        [
                            "org_name" => "Yellow Banana"
                        ],
                        [
                            "org_name" => "Brown Banana"
                        ],
                        [
                            "org_name" => "Green Banana"
                        ],
                        [
                            "org_name" => "Black Banana",
                            "daughters" => [
                                ["org_name" => "Phoneutria Spider"]
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
