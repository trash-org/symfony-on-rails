<?php

namespace App\Bundle\Rails\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PackageController extends AbstractController
{

    protected $layoutRender = 'layout/main';

    public function actionGroups()
    {
        $all = \App::$domain->package->group->all();
        dd($all);
        //return $this->render('sandbox/index', ['data' => $all]);
    }

    public function actionOneGroup()
    {
        $all = \App::$domain->package->group->oneByName('php7rails');
        dd($all);
        return $this->render('sandbox/index', ['data' => $all]);
    }

    public function actionPackages()
    {
        $all = \App::$domain->package->package->all();
        dd($all);
        return $this->render('sandbox/index', ['data' => $all]);
    }

    public function actionOnePackage()
    {
        $all = \App::$domain->package->package->oneById('php7rails/app');
        dd($all);
        return $this->render('sandbox/index', ['data' => $all]);
    }

}
