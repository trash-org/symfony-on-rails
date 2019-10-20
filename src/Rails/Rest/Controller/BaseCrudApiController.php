<?php

namespace App\Rails\Rest\Controller;

use App\Rails\Rest\Action\BaseAction;
use App\Rails\Rest\Action\BaseEntityAction;
use App\Rails\Rest\Action\CreateAction;
use App\Rails\Rest\Action\DeleteAction;
use App\Rails\Rest\Action\IndexAction;
use App\Rails\Rest\Action\OptionsAction;
use App\Rails\Rest\Action\UpdateAction;
use App\Rails\Rest\Action\ViewAction;
use Symfony\Component\HttpFoundation\Request;

class BaseCrudApiController
{

    /** @var $service object */
    public $service;

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
            ],
            'create' => [
                'class' => CreateAction::class,
            ],
            'view' => [
                'class' => ViewAction::class,
            ],
            'update' => [
                'class' => UpdateAction::class,
            ],
            'delete' => [
                'class' => DeleteAction::class,
            ],
            'options' => [
                'class' => OptionsAction::class,
            ],
        ];
    }

    public function index(Request $request)
    {
        $actions = $this->actions();
        $actionClass = $actions['index']['class'];
        /** @var BaseAction $action */
        $action = new $actionClass($this->service, $request);
        return $action->run();
    }

    public function create(Request $request)
    {
        $actions = $this->actions();
        $actionClass = $actions['create']['class'];
        /** @var BaseAction $action */
        $action = new $actionClass($this->service, $request);
        return $action->run();
    }

    public function view($id, Request $request)
    {
        $actions = $this->actions();
        $actionClass = $actions['view']['class'];
        /** @var BaseEntityAction $action */
        $action = new $actionClass($this->service, $request, $id);
        return $action->run();
    }

    public function update($id, Request $request)
    {
        $actions = $this->actions();
        $actionClass = $actions['update']['class'];
        /** @var BaseEntityAction $action */
        $action = new $actionClass($this->service, $request, $id);
        return $action->run();
    }

    public function delete($id, Request $request)
    {
        $actions = $this->actions();
        $actionClass = $actions['delete']['class'];
        /** @var BaseEntityAction $action */
        $action = new $actionClass($this->service, $request, $id);
        return $action->run();
    }

    public function options(Request $request)
    {
        $actions = $this->actions();
        $actionClass = $actions['options']['class'];
        /** @var BaseEntityAction $action */
        $action = new $actionClass($this->service, $request);
        return $action->run();
    }

}