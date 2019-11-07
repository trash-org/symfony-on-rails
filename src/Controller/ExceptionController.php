<?php

namespace App\Controller;

use PhpLab\Rest\Lib\JsonRestSerializer;
use PhpLab\Domain\Helper\RestApiHelper;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ExceptionController extends \Symfony\Bundle\TwigBundle\Controller\ExceptionController
{

    public function showException(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null) {
        $isApi = RestApiHelper::parseVersionFromUrl($request->getRequestUri());
        if($isApi) {
            $response = new JsonResponse;
            $serializer = new JsonRestSerializer($response);
            $serializer->serializeException($exception);
            return $response;
        } else {
            return parent::showAction($request,  $exception, $logger);
        }
    }

}