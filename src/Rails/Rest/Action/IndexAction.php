<?php

namespace App\Rails\Rest\Action;

use App\Rails\Domain\Data\DataProvider;
use App\Rails\Domain\Data\JsonSerializer;
use App\Rails\Rest\Helper\RequestHelper;
use php7rails\domain\data\GetParams;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexAction extends BaseAction
{

    public function run() : JsonResponse {
        $response = new JsonResponse;
        //$query = RequestHelper::forgeQueryFromRequest($this->request);
        $getParams = new GetParams();
        $query = $getParams->getAllParams($this->request->query->all());
        $dp = new DataProvider([
            'service' => $this->service,
            'query' => $query,
            'page' => $this->request->get("page", 1),
            'pageSize' => $this->request->get("per-page", 10),
        ]);
        $serializer = new JsonSerializer($response);
        $serializer->serializeDataProviderEntity($dp->getAll());
        return $response;
    }

}