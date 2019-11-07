<?php

namespace App\Rails\Rest\Action;

use App\Rails\Domain\Data\DataProvider;
use App\Rails\Rest\Lib\JsonRestSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexAction extends BaseAction
{

    public function run() : JsonResponse {
        $response = new JsonResponse;
        $dp = new DataProvider([
            'service' => $this->service,
            'query' => $this->query,
            'page' => $this->request->get("page", 1),
            'pageSize' => $this->request->get("per-page", 10),
        ]);
        $serializer = new JsonRestSerializer($response);
        $serializer->serializeDataProviderEntity($dp->getAll());
        return $response;
    }

}