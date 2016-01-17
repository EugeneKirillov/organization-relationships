<?php
namespace Owr\Tests\Functional\Api;

use Owr\Tests\Functional\WebTestCase;

class OrganizationsTest extends WebTestCase
{
    public function testGetRelations()
    {
        $params = [
            'page'  => 1,
            'count' => 2,
        ];
        $this->client->request('GET', '/api/v1/organizations/relations/Jungle Tree', $params);

        $expected = array_slice([
            [
                "relationship_type"=> "self",
                "org_name" => "Jungle Tree",
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
        ], ($params['page'] - 1) * $params['count'], $params['count']);
        $this->assertJsonResponse($expected, 200);
    }

    public function testCreateRelations()
    {
        $this->client->request('POST', '/api/v1/organizations/relations');

        $expected = [
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
                            "org_name"  => "Black Banana",
                            "daughters" => [
                                ["org_name" => "Phoneutria Spider"],
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
                            "org_name"  => "Black Banana",
                            "daughters" => [
                                ["org_name" => "Phoneutria Spider"],
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $this->assertJsonResponse($expected, 201);
    }

    public function testDeleteRelations()
    {
        $this->client->request('DELETE', '/api/v1/organizations/relations');

        $this->assertStatusCode(204);
        $this->assertEquals("null", $this->client->getResponse());
    }
}
