<?php

namespace Tests\Bundle\Article\Controller;

use App\Rails\Test\BaseRestTest;
use php7extension\core\web\enums\HttpStatusCodeEnum;

class DefaultControllerTest extends BaseRestTest
{

    protected $baseUrl = 'http://symfony4.lab';
    protected $basePath = 'api/v1/';
    protected $lastId;

    public function testIndex()
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
            ],
            [
                "id" => 6,
                "title" => null,
            ],
            [
                "id" => 7,
                "title" => null,
            ],
            [
                "id" => 8,
                "title" => null,
            ]
        ];
        $this->assertBody($response, $actualBody);
        $this->assertPagination($response, null, 2, 4);
        $this->assertEquals(HttpStatusCodeEnum::OK, $response->getStatusCode());
    }

    public function testIndexById()
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
        ];
        $this->assertBody($response, $actualBody);
        $this->assertEquals(HttpStatusCodeEnum::OK, $response->getStatusCode());
    }

    public function testViewNotFound()
    {
        $response = $this->sendGet('article/3333');
        $this->assertEquals(HttpStatusCodeEnum::NOT_FOUND, $response->getStatusCode());
    }

    public function testCreate()
    {
        $response = $this->sendPost('article', [
            'title' => 'test123',
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
        ]);

        $response = $this->sendDelete('article/' . $lastId);
        $this->assertEquals(HttpStatusCodeEnum::NO_CONTENT, $response->getStatusCode());

        $responseView = $this->sendGet('article/' . $lastId);
        $this->assertEquals(HttpStatusCodeEnum::NOT_FOUND, $responseView->getStatusCode());
    }

}
