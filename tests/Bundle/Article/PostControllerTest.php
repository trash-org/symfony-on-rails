<?php

namespace Tests\Bundle\Article\Controller;

use App\Rails\Test\BaseRestTest;
use php7extension\core\web\enums\HttpStatusCodeEnum;

class PostControllerTest extends BaseRestTest
{

    protected $baseUrl = 'http://symfony4.lab';
    protected $basePath = 'api/v1/';
    protected $lastId;

    public function testAll()
    {
        $response = $this->sendGet('article', [
            'per-page' => '4',
            'page' => '2',
        ]);

        $actualBody = [
            [
                "id" => 5,
                "title" => '5555',
                'category_id' => 2,
                'category' => null,
            ],
            [
                "id" => 6,
                "title" => '6666',
                'category_id' => 3,
                'category' => null,
            ],
            [
                "id" => 7,
                "title" => '7777',
                'category_id' => 1,
                'category' => null,
            ],
            [
                "id" => 8,
                "title" => '8888',
                'category_id' => 2,
                'category' => null,
            ]
        ];
        $this->assertBody($response, $actualBody);
        $this->assertPagination($response, null, 2, 4);
        $this->assertEquals(HttpStatusCodeEnum::OK, $response->getStatusCode());
    }

    public function testAllWithRelations()
    {
        $response = $this->sendGet('article', [
            'per-page' => '4',
            'page' => '2',
            'expand' => 'category',
        ]);

        $actualBody = [
            [
                "id" => 5,
                "title" => '5555',
                'category_id' => 2,
                'category' => [
                    'id' => 2,
                    'title' => '222',
                ],
            ],
            [
                "id" => 6,
                "title" => '6666',
                'category_id' => 3,
                'category' => [
                    'id' => 3,
                    'title' => '333',
                ],
            ],
            [
                "id" => 7,
                "title" => '7777',
                'category_id' => 1,
                'category' => [
                    'id' => 1,
                    'title' => '111',
                ],
            ],
            [
                "id" => 8,
                "title" => '8888',
                'category_id' => 2,
                'category' => [
                    'id' => 2,
                    'title' => '222',
                ],
            ]
        ];
        $this->assertBody($response, $actualBody);
        $this->assertPagination($response, null, 2, 4);
        $this->assertEquals(HttpStatusCodeEnum::OK, $response->getStatusCode());
    }

    public function testAllSortByCategory()
    {
        $response = $this->sendGet('article', [
            'per-page' => '4',
            'page' => '2',
            'sort' => 'category_id,id',
        ]);

        $actualBody = [
            [
                'id' => 5,
                'category_id' => 2,
                'title' => '5555',
                'category' => null,
            ],
            [
                'id' => 8,
                'category_id' => 2,
                'title' => '8888',
                'category' => null,
            ],
            [
                'id' => 3,
                'category_id' => 3,
                'title' => '3333',
                'category' => null,
            ],
            [
                'id' => 6,
                'category_id' => 3,
                'title' => '6666',
                'category' => null,
            ],
        ];
        $this->assertBody($response, $actualBody);
        $this->assertPagination($response, null, 2, 4);
        $this->assertEquals(HttpStatusCodeEnum::OK, $response->getStatusCode());
    }

    public function testAllSortByCategoryDesc()
    {
        $response = $this->sendGet('article', [
            'per-page' => '4',
            'page' => '2',
            'sort' => '-category_id,id',
        ]);

        $actualBody = [
            [
                'id' => 5,
                'category_id' => 2,
                'title' => '5555',
                'category' => null,
            ],
            [
                'id' => 8,
                'category_id' => 2,
                'title' => '8888',
                'category' => null,
            ],
            [
                'id' => 1,
                'category_id' => 1,
                'title' => '1111',
                'category' => null,
            ],
            [
                'id' => 4,
                'category_id' => 1,
                'title' => '4444',
                'category' => null,
            ],
        ];
        $this->assertBody($response, $actualBody);
        $this->assertPagination($response, null, 2, 4);
        $this->assertEquals(HttpStatusCodeEnum::OK, $response->getStatusCode());
    }

    /*public function testAllOnlyFields()
    {
        $response = $this->sendGet('article', [
            'per-page' => '4',
            'page' => '2',
            'fields' => 'id',
        ]);

        $actualBody = [
            [
                "id" => 5,
                "title" => null,
                'category_id' => null,
            ],
            [
                "id" => 6,
                "title" => null,
                'category_id' => null,
            ],
            [
                "id" => 7,
                "title" => null,
                'category_id' => null,
            ],
            [
                "id" => 8,
                "title" => null,
                'category_id' => null,
            ]
        ];
        $this->assertBody($response, $actualBody);
        $this->assertPagination($response, null, 2, 4);
        $this->assertEquals(HttpStatusCodeEnum::OK, $response->getStatusCode());
    }*/

    public function testAllById()
    {
        $response = $this->sendGet('article', [
            'per-page' => '4',
            'page' => '2',
            'fields' => 'id',
            'id' => '3',
        ]);

        $actualBody = [
            [
                "id" => 3,
                "title" => null,
                'category_id' => null,
                'category' => null,
            ],
        ];
        $this->assertBody($response, $actualBody);

        $this->assertPagination($response, 1, 1, 4);
        $this->assertEquals(HttpStatusCodeEnum::OK, $response->getStatusCode());
    }

    public function testView()
    {
        $response = $this->sendGet('article/3');

        $actualBody = [
            'id' => 3,
            'title' => '3333',
            'category_id' => 3,
            'category' => null,
        ];
        $this->assertBody($response, $actualBody);
        $this->assertEquals(HttpStatusCodeEnum::OK, $response->getStatusCode());
    }

    public function testViewWithRelations()
    {
        $response = $this->sendGet('article/3', [
            'expand' => 'category',
        ]);

        $actualBody = [
            'id' => 3,
            'title' => '3333',
            'category_id' => 3,
            'category' => [
                'id' => 3,
                'title' => '333',
            ],
        ];
        $this->assertBody($response, $actualBody);
        $this->assertEquals(HttpStatusCodeEnum::OK, $response->getStatusCode());
    }

    public function testViewNotFound()
    {
        $response = $this->sendGet('article/3333');
        $this->assertEquals(HttpStatusCodeEnum::NOT_FOUND, $response->getStatusCode());
    }

    /*public function testBadCreate()
    {
        $response = $this->sendPost('article', [
            'title' => 'test123',
        ]);
        $this->assertEquals(HttpStatusCodeEnum::UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }*/

    public function testCreate()
    {
        $response = $this->sendPost('article', [
            'title' => 'test123',
            'category_id' => 3,
        ]);
        $this->assertCreated($response);
        $lastId = $this->getLastInsertId($response);
        $responseView = $this->sendGet('article/' . $lastId);
        $this->assertEquals(HttpStatusCodeEnum::OK, $responseView->getStatusCode());

        $response = $this->sendPut('article/' . $lastId, ['title' => 'qwerty']);
        $this->assertEquals(HttpStatusCodeEnum::NO_CONTENT, $response->getStatusCode());

        $responseView = $this->sendGet('article/' . $lastId);
        $this->assertBody($responseView, [
            'id' => $lastId,
            'title' => 'qwerty',
            'category_id' => 3,
            'category' => null,
        ]);

        $response = $this->sendDelete('article/' . $lastId);
        $this->assertEquals(HttpStatusCodeEnum::NO_CONTENT, $response->getStatusCode());

        $responseView = $this->sendGet('article/' . $lastId);
        $this->assertEquals(HttpStatusCodeEnum::NOT_FOUND, $responseView->getStatusCode());
    }

    public function testMethodAllowed()
    {
        $response = $this->sendPost('article/1');
        $this->assertEquals(HttpStatusCodeEnum::METHOD_NOT_ALLOWED, $response->getStatusCode());

        $response = $this->sendPut('article');
        $this->assertEquals(HttpStatusCodeEnum::METHOD_NOT_ALLOWED, $response->getStatusCode());

        $response = $this->sendDelete('article');
        $this->assertEquals(HttpStatusCodeEnum::METHOD_NOT_ALLOWED, $response->getStatusCode());
    }

    public function testOptions()
    {
        $response = $this->sendOptions('article/1');

        $this->assertCors($response, '*', null, ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD', 'PATCH', 'TRACE', 'CONNECT']);
    }

    public function testNotRoute()
    {
        $response = $this->sendGet('article-possst/1');

        $this->assertEquals(HttpStatusCodeEnum::NOT_FOUND, $response->getStatusCode());
    }

}
