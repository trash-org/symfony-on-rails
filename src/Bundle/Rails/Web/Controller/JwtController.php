<?php

namespace App\Bundle\Rails\Web\Controller;

use App;
use php7extension\core\controller\base\BaseWebController;
use php7extension\core\develop\helpers\Benchmark;
use php7extension\crypt\domain\entities\JwtEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JwtController extends AbstractController
{

    protected $layoutRender = 'layout/main';

    public function actionIndex() {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => 123456,
        ];

        Benchmark::begin('jwt_generate');
        $token = \App::$container->jwt->sign($jwtEntity, 'auth');
        Benchmark::end('jwt_generate');

        Benchmark::begin('jwt_decode');
        $tokenEntity = \App::$container->jwt->verify($token, 'auth');
        Benchmark::end('jwt_decode');

        dd(Benchmark::allFlat());

        //return $this->render('sandbox/index', ['data' => Benchmark::allFlat()]);
    }

}
