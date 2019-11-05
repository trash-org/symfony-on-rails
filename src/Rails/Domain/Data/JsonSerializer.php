<?php

namespace App\Rails\Domain\Data;

use App\Rails\Domain\Data\ArraySerializerHandlers\ArrayHandler;
use App\Rails\Domain\Data\ArraySerializerHandlers\ObjectHandler;
use App\Rails\Domain\Data\ArraySerializerHandlers\TimeHandler;
use php7extension\core\web\enums\HttpHeaderEnum;
use php7extension\yii\helpers\ArrayHelper;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonSerializer
{

    private $response;

    public function __construct(JsonResponse $response = null)
    {
        if( ! $response instanceof JsonResponse) {
            $response = new JsonResponse;
        }
        $this->response = $response;
        //$this->response->headers->set(HttpHeaderEnum::CONTENT_TYPE, 'application/json');
    }

    public function serializeException(FlattenException $exception) {
        if(in_array($exception->getClass(), ['Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException', 'php7extension\\core\\exceptions\\NotFoundException'])) {
            $this->response->setStatusCode(404);
        } elseif($exception->getClass() == 'Symfony\\Component\\HttpKernel\\Exception\\MethodNotAllowedHttpException') {
            $this->response->setStatusCode(405);
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

        /*$serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $data = $serializer->serialize($data, 'json');
        $this->response->setContent($data);*/

        if($data instanceof Collection) {
            $data = $data->toArray();
        }
        if(is_array($data) || is_object($data)) {
            //$data = ArrayHelper::toArray($data);
            $arraySerializer = new ArraySerializer([
                ArrayHandler::class,
                TimeHandler::class,
                ObjectHandler::class,
            ]);
            $data = $arraySerializer->toArray($data);

        }
        $this->response->setData($data);
    }

}