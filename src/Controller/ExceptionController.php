<?php

namespace App\Controller;

use App\Rails\Domain\Data\JsonSerializer;
use App\Rails\Domain\Helper\RestApiHelper;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ExceptionController extends \Symfony\Bundle\TwigBundle\Controller\ExceptionController
{

    public function showException(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null) {
        $isApi = RestApiHelper::parseVersionFromUrl($request->getRequestUri());
        if($isApi) {
            $serializer = new JsonSerializer;
            $serializer->serializeException($exception);
            return $serializer->getResponse();
        } else {
            return parent::showAction($request,  $exception, $logger);
        }
    }

}