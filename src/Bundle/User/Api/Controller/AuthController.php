<?php

namespace App\Bundle\User\Api\Controller;

use App\Bundle\User\Domain\Entity\User;
use App\Bundle\User\Domain\Form\AuthForm;
use App\Bundle\User\Domain\Service\AuthService;
use App\Bundle\User\Domain\Service\TokenAuthenticator;
use php7extension\core\common\helpers\StringHelper;
use php7extension\core\web\enums\HttpHeaderEnum;
use php7extension\yii\helpers\ArrayHelper;
use PhpLab\Domain\Exceptions\UnprocessibleEntityException;
use PhpLab\Rest\Lib\JsonRestSerializer;
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

    public function index(Request $request)
    {
        $response = new JsonResponse;
        try {
            /** @var User $userEntity */
            $userEntity = $this->authService->info();
            $serializer = new JsonRestSerializer($response);
            $serializer->serialize($userEntity);
        } catch (\Exception $e) {
            $response->setData($e->getMessage());
        }
        return $response;
    }

    public function login(Request $request)
    {
        $response = new JsonResponse();
        $authForm = new AuthForm($request->request->all());
        try {
            /** @var User $userEntity */
            $userEntity = $this->authService->authentication($authForm);
            $response->headers->set(HttpHeaderEnum::AUTHORIZATION, $userEntity->getApiToken());
            $serializer = new JsonRestSerializer($response);
            $serializer->serialize($userEntity);
        } catch (UnprocessibleEntityException $e) {
            $errorCollection = $e->getErrorCollection();
            $serializer = new JsonRestSerializer($response);
            $serializer->serialize($errorCollection);
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $response;
    }

}