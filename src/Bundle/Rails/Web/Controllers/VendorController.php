<?php

namespace App\Bundle\Rails\Web\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VendorController extends AbstractController
{

    protected $layoutRender = 'layout/main';

    public function actionAllChanged()
    {
        $all = \App::$domain->vendor->info->allChanged();
        dd($all);
        return $this->render('sandbox/index', ['data' => $all]);
    }

    public function actionAllForRelease()
    {
        $all = \App::$domain->vendor->info->allForRelease();
        dd($all);
        return $this->render('sandbox/index', ['data' => $all]);
    }

    public function actionAllVersion()
    {
        $all = \App::$domain->vendor->info->allVersion();
        dd($all);
        return $this->render('sandbox/index', ['data' => $all]);
    }

}
