<?php
namespace Owr\Tests\Functional\Api;

use Owr\Tests\Functional\WebTestCase;

class OrganizationsTest extends WebTestCase
{
    public function testGetRelations()
    {
        // TODO: use fixtures instead of this POST request
        $content = json_decode($this->getPayload(), true);
        $this->client->request('POST', '/api/v1/organizations/relations', [], [], $content);

        $params = [
            'page'  => 1,
            'count' => 2,
        ];
        $this->client->request('GET', '/api/v1/organizations/relations/Black Banana', $params);

        $expected = array_slice([
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
        $content = json_decode($this->getPayload(), true);
        $this->client->request('POST', '/api/v1/organizations/relations', [], [], $content);

        $expected = [
            "org_id"    => 1,
            "org_name"  => "Paradise Island",
            "daughters" => [
                [
                    "org_id"    => 2,
                    "org_name"  => "Banana tree",
                    "daughters" => [
                        [
                            "org_id"   => 3,
                            "org_name" => "Yellow Banana"
                        ],
                        [
                            "org_id"   => 4,
                            "org_name" => "Brown Banana"
                        ],
                        [
                            "org_id"    => 5,
                            "org_name"  => "Black Banana",
                            "daughters" => [
                                [
                                    "org_id"   => 6,
                                    "org_name" => "Phoneutria Spider"
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    "org_id"    => 7,
                    "org_name"  => "Big banana tree",
                    "daughters" => [
                        [
                            "org_id"   => 3,
                            "org_name" => "Yellow Banana"
                        ],
                        [
                            "org_id"   => 4,
                            "org_name" => "Brown Banana"
                        ],
                        [
                            "org_id"   => 8,
                            "org_name" => "Green Banana"
                        ],
                        [
                            "org_id"    => 5,
                            "org_name"  => "Black Banana",
                            "daughters" => [
                                [
                                    "org_id"   => 6,
                                    "org_name" => "Phoneutria Spider"
                                ],
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

    protected function getPayload()
    {
        return <<<'JSON'
{
    "org_name": "Paradise Island",
    "daughters": [
        {
            "org_name": "Banana tree",
            "daughters": [
                {
                    "org_name": "Yellow Banana"
                },
                {
                    "org_name": "Brown Banana"
                },
                {
                    "org_name": "Black Banana",
                    "daughters": [
                        {
                            "org_name": "Phoneutria Spider"
                        }
                    ]
                }
            ]
        },
        {
            "org_name": "Big banana tree",
            "daughters": [
                {
                    "org_name": "Yellow Banana"
                },
                {
                    "org_name": "Brown Banana"
                },
                {
                    "org_name": "Green Banana"
                },
                {
                    "org_name": "Black Banana"
                }
            ]
        }
    ]
}
JSON;
    }
}
