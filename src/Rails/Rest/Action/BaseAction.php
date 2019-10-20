<?php

namespace App\Rails\Rest\Action;

use App\Rails\Domain\Interfaces\CrudServiceInterface;
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

    public function __construct(object $service, Request $request)
    {
        $this->service = $service;
        $this->request = $request;
    }

    abstract public function run() : JsonResponse;

}