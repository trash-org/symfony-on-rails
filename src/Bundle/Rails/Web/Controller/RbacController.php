<?php

namespace App\Bundle\Rails\Web\Controller;

use php7extension\core\controller\base\BaseWebController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RbacController extends AbstractController
{

    protected $layoutRender = 'layout/main';

    public function actionRole() {
        $data = \App::$domain->rbac->role->all();
        dd($data);
        return $this->render('sandbox/index', ['data' => $data]);
    }

    public function actionPermission() {
        $data = \App::$domain->rbac->permission->all();
        dd($data);
        return $this->render('sandbox/index', ['data' => $data]);
    }

}