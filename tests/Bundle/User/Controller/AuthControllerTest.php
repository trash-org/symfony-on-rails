<?php

namespace Tests\Bundle\User\Controller;

use php7extension\core\web\enums\HttpStatusCodeEnum;

class AuthControllerTest extends \PhpExample\Bundle\Tests\rest\Messenger\ChatControllerTest
{

    protected $basePath = 'api/v1/';

    public function testAuthBadPassword()
    {
        $response = $this->sendPost('auth', [
            'login' => 'user1',
            'password' => 'Wwwqqq11133333',
        ]);

        $actualBody = [
            [
                'field' => 'password',
                'message' => 'Bad password',
            ]
        ];
        $this->assertBody($response, $actualBody);
        $this->assertEquals(HttpStatusCodeEnum::UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testAuthNotFoundLogin()
    {
        $response = $this->sendPost('auth', [
            'login' => 'qwerty',
            'password' => 'Wwwqqq111',
        ]);

        $actualBody = [
            [
                'field' => 'login',
                'message' => 'User not found',
            ]
        ];
        $this->assertBody($response, $actualBody);
        $this->assertEquals(HttpStatusCodeEnum::UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testAuth()
    {
        $response = $this->sendPost('auth', [
            'login' => 'user1',
            'password' => 'Wwwqqq111',
        ]);

        $actualBody = [
            'id' => 1,
            'username' => 'user1',
            'username_canonical' => 'user1',
            'email' => 'user1@example.com',
            'email_canonical' => 'user1@example.com',
            'roles' => [
                'ROLE_USER',
                'ROLE_ADMIN',
            ],
        ];
        $this->assertBody($response, $actualBody);
        $body = $this->getBody($response);
        $this->assertNotEmpty(preg_match('#jwt\s[\s\S]+\.[\s\S]+\.[\s\S]+#i', $body['api_token']));
        $this->assertEquals(HttpStatusCodeEnum::OK, $response->getStatusCode());
        $this->assertFalse(isset($body['password']));
    }

}
