<?php

namespace App\Rails\Rest\Action;

use App\Rails\Rest\Lib\JsonRestSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;

class ViewAction extends BaseEntityAction
{

    public function run() : JsonResponse {
        $response = new JsonResponse;
        $entity = $this->service->oneById($this->id, $this->query);
        $serializer = new JsonRestSerializer($response);
        $serializer->serialize($entity);
        return $response;
    }

}