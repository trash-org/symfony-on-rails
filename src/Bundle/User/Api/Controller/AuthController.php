<?php

namespace App\Bundle\User\Api\Controller;

use App\Bundle\User\Domain\Form\AuthForm;
use App\Bundle\User\Domain\Service\AuthService;
use php7extension\core\exceptions\NotFoundException;
use php7extension\core\web\enums\HttpHeaderEnum;
use php7extension\yii\helpers\ArrayHelper;
use php7rails\domain\exceptions\UnprocessableEntityHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends AbstractController
{

    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        //$login = $request->query->get('login');
        //$password = $request->query->get('password');
        $response = new JsonResponse();
        try {
            //$identity = \App::$domain->account->auth->authentication($login, $password);

            $authForm = new AuthForm($request->query->all());
            $identity = $this->authService->authentication($authForm);

            $response->setData(ArrayHelper::toArray($identity));
            $response->headers->set(HttpHeaderEnum::AUTHORIZATION, $identity->token);
        } catch (\Exception $e) {
            $response->setData($e->getMessage());
        }
        return $response;
    }

}