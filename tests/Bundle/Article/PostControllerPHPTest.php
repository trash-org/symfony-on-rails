<?php

namespace Tests\Bundle\Article\Controller;

use PhpLab\Test\BaseRestTest;
use php7extension\core\web\enums\HttpMethodEnum;
use php7extension\core\web\enums\HttpStatusCodeEnum;

class PostControllerPHPTest extends BaseRestTest
{

    protected $baseUrl = 'http://symfony-on-rails.lab';
    protected $basePath = 'php/v1/';
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
                "title" => 'post 5',
                'category_id' => 2,
                'category' => null,
            ],
            [
                "id" => 6,
                "title" => 'post 6',
                'category_id' => 3,
                'category' => null,
            ],
            [
                "id" => 7,
                "title" => 'post 7',
                'category_id' => 1,
                'category' => null,
            ],
            [
                "id" => 8,
                "title" => 'post 8',
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
                'category' => [
                    'id' => 2,
                ],
            ],
            [
                "id" => 6,
                'category' => [
                    'id' => 3,
                ],
            ],
            [
                "id" => 7,
                'category' => [
                    'id' => 1,
                ],
            ],
            [
                "id" => 8,
                'category' => [
                    'id' => 2,
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

        $body = $this->getBody($response);
        $this->assertOrder($body, 'category_id', SORT_ASC);

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

        $body = $this->getBody($response);
        $this->assertOrder($body, 'category_id', SORT_DESC);

        $this->assertPagination($response, null, 2, 4);
        $this->assertEquals(HttpStatusCodeEnum::OK, $response->getStatusCode());
    }

    public function testAllOnlyFields()
    {
        $response = $this->sendGet('article', [
            'per-page' => '2',
            'fields' => 'id',
            'sort' => 'id',
        ]);

        $actualBody = [
            [
                "id" => 1,
                "title" => null,
                'category_id' => null,
            ],
            [
                "id" => 2,
                "title" => null,
                'category_id' => null,
            ],
        ];
        $this->assertBody($response, $actualBody);
        //$this->assertPagination($response, null, 2, 2);
        $this->assertEquals(HttpStatusCodeEnum::OK, $response->getStatusCode());
    }

    public function testAllById()
    {
        $response = $this->sendGet('article', [
            'per-page' => '4',
            'page' => '2',
            'id' => '3',
        ]);

        $actualBody = [
            [
                "id" => 3,
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
            'title' => 'post 3',
            'category_id' => 3,
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
            'category' => [
                'id' => 3,
                'title' => 'category 3',
            ],
        ];
        $this->assertBody($response, $actualBody);
        $this->assertEquals(HttpStatusCodeEnum::OK, $response->getStatusCode());
    }

    public function testViewNotFound()
    {
        $response = $this->sendGet('article/3333');
        //dd($response);
        $this->assertEquals(HttpStatusCodeEnum::NOT_FOUND, $response->getStatusCode());
    }

    /*public function testBadCreate()
    {
        $response = $this->sendPost('article', [
            'title' => 'test123',
        ]);
        $this->assertEquals(HttpStatusCodeEnum::UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }*/

    /*public function testCreate()
    {
        $data = [
            'title' => 'test123',
            'category_id' => 3,
        ];
        $response = $this->sendPost('article', $data);
        $this->assertCreated($response);
        $lastId = $this->getLastInsertId($response);
        $responseView = $this->sendGet('article/' . $lastId);
        $this->assertEquals(HttpStatusCodeEnum::OK, $responseView->getStatusCode());
        $this->assertBody($responseView, [
            'id' => $lastId,
            'title' => 'test123',
            'category_id' => 3,
        ]);

        $response = $this->sendPut('article/' . $lastId, ['title' => 'qwerty']);
        $this->assertEquals(HttpStatusCodeEnum::NO_CONTENT, $response->getStatusCode());

        $responseView = $this->sendGet('article/' . $lastId);
        $this->assertBody($responseView, [
            'id' => $lastId,
            'title' => 'qwerty',
            'category_id' => 3,
        ]);

        $response = $this->sendDelete('article/' . $lastId);
        $this->assertEquals(HttpStatusCodeEnum::NO_CONTENT, $response->getStatusCode());

        $responseView = $this->sendGet('article/' . $lastId);
        $this->assertEquals(HttpStatusCodeEnum::NOT_FOUND, $responseView->getStatusCode());
    }*/

    /*public function testMethodAllowed()
    {
        $response = $this->sendPost('article/1');
        $this->assertEquals(HttpStatusCodeEnum::METHOD_NOT_ALLOWED, $response->getStatusCode());

        $response = $this->sendPut('article');
        $this->assertEquals(HttpStatusCodeEnum::METHOD_NOT_ALLOWED, $response->getStatusCode());

        $response = $this->sendDelete('article');
        $this->assertEquals(HttpStatusCodeEnum::METHOD_NOT_ALLOWED, $response->getStatusCode());
    }*/

    /*public function testOptions()
    {
        $response = $this->sendOptions('article/1');

        $this->assertCors($response, '*', null, [
            HttpMethodEnum::GET,
            HttpMethodEnum::POST,
            HttpMethodEnum::PUT,
            HttpMethodEnum::DELETE,
            HttpMethodEnum::OPTIONS,
        ]);
    }*/

    public function testNotRoute()
    {
        $response = $this->sendGet('article-possst/1');

        $this->assertEquals(HttpStatusCodeEnum::NOT_FOUND, $response->getStatusCode());
    }

}
