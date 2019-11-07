<?php

namespace App\Rails\Rest\Action;

use App\Rails\Domain\Interfaces\CrudServiceInterface;
use php7rails\domain\data\GetParams;
use php7rails\domain\data\Query;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseAction
 * @package App\Rails\Rest\Action
 *
 * @property CrudServiceInterface $service
 */
abstract class BaseAction
{

    /** @var $service */
    public $service;

    /** @var Request */
    public $request;

    /** @var Query */
    public $query;

    public function __construct(object $service, Request $request)
    {
        $this->service = $service;
        $this->request = $request;
        $this->query = $this->forgeQueryFromRequest($request);
    }

    abstract public function run() : JsonResponse;

    private function forgeQueryFromRequest(Request $request) {
        $getParams = new GetParams;
        return $getParams->getAllParams($request->query->all());
    }

}