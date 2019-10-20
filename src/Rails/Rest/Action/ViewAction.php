<?php

namespace App\Rails\Rest\Action;

use App\Rails\Domain\Data\JsonSerializer;
use php7rails\domain\data\GetParams;
use Symfony\Component\HttpFoundation\JsonResponse;

class ViewAction extends BaseEntityAction
{

    public function run() : JsonResponse {

        //dd($this->request->query->all());
        $getParams = new GetParams();
        $query = $getParams->getAllParams($this->request->query->all());

        /*if($queryParams === null) {
            $queryParams = \Yii::$app->request->get();
        }
        */

        $entity = $this->service->oneById($this->id, $query);
        $serializer = new JsonSerializer();
        $serializer->setData($entity);
        return $serializer->getResponse();
    }

}