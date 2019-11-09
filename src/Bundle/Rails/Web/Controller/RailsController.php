<?php

namespace App\Bundle\Rails\Web\Controller;

use php7extension\core\develop\helpers\Benchmark;
use php7extension\yii\helpers\ArrayHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class RailsController extends AbstractController
{

    public function actionIndex()
    {
        return $this->render('rails/index.html.twig', [
            //'number' => $number,
        ]);
    }

    public function actionDb()
    {
        $queriesCount = 50;
        $query = 'SELECT * FROM "common"."migration" LIMIT 100';

        Benchmark::begin('db_test');
        for ($i = 0; $i <= $queriesCount; $i++) {
            $results = \App::$domain->db->main->createCommand($query)->queryAll();
        }
        Benchmark::end('db_test');

        dd([
            'queriesCount' => $queriesCount,
            'countRows' => count($results),
            'benchmark' => Benchmark::allFlat()['db_test'],
        ]);

        return $this->render('default/index');
    }

    public function actionRbacRoles()
    {
        $data = \App::$domain->rbac->role->all();
        //dd($data);
        $response = new JsonResponse();
        $response->setData(ArrayHelper::toArray($data));
        return $response;
    }

    public function actionSecurity()
    {

        $str = \App::$container->security->generateRandomString();
        dd($str);

        return $this->render('default/index');
    }

}
