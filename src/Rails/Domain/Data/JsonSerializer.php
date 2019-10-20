<?php

namespace App\Rails\Domain\Data;

use App\Rails\Domain\Helper\EntityHelper;
use php7extension\core\web\enums\HttpHeaderEnum;
use php7extension\yii\helpers\ArrayHelper;
use php7extension\yii\helpers\Inflector;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\VarDumper\Caster\ReflectionCaster;
use Symfony\Component\VarDumper\Cloner\VarCloner;

class JsonSerializer
{

    private $response;

    public function __construct(JsonResponse $response = null)
    {
        if( ! $response instanceof JsonResponse) {
            $response = new JsonResponse;
        }
        $this->response = $response;
    }

    public function serializeException(FlattenException $exception) {

        if($exception->getClass() == 'php7extension\\core\\exceptions\\NotFoundException') {
            $this->response->setStatusCode(404);
        } else {
            $this->response->setStatusCode(500);
        }

        $data = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'status' => $this->response->getStatusCode(),
            'type' => $exception->getClass(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
            'previous' => $exception->getPrevious(),
        ];

        if($_SERVER['APP_ENV'] !== 'dev') {
            unset($data['previous']);
            unset($data['file']);
            unset($data['line']);
            unset($data['trace']);
        }

        $this->setData($data);
        return $this;
    }

    public function serializeDataProviderEntity(DataProviderEntity $entity) {
        $this->setData($entity->collection);
        $this->response->headers->set(HttpHeaderEnum::PER_PAGE, $entity->pageSize);
        $this->response->headers->set(HttpHeaderEnum::PAGE_COUNT, $entity->pageCount);
        $this->response->headers->set(HttpHeaderEnum::TOTAL_COUNT, $entity->totalCount);
        $this->response->headers->set(HttpHeaderEnum::CURRENT_PAGE, $entity->page);
        return $this;
    }

    public function getResponse() {
        return $this->response;
    }

    public function setData($data) {

        //$serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        //$rr = $serializer->serialize($data, 'json');
//dd($rr);
        /*$methods = get_class_methods($data);
        foreach ($methods as &$method) {
            $method = substr($method, 3);
            $method = Inflector::camel2id($method);
        }
        $methods = array_unique($methods);*/

        //$methods = EntityHelper::toArray($data);
        //dd($methods);

        if(is_array($data) || is_object($data)) {
            $data = ArrayHelper::toArray($data);
        }
        $this->response->setData($data);
    }

}