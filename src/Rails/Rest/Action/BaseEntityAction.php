<?php

namespace App\Rails\Rest\Action;

use App\Rails\Domain\Interfaces\CrudServiceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseEntityAction
 * @package App\Rails\Rest\Action
 *
 * @property CrudServiceInterface $service
 */
abstract class BaseEntityAction extends BaseAction
{

    public $id;

    public function __construct(object $service, Request $request, $id)
    {
        parent::__construct($service, $request);
        $this->id = $id;
    }

}