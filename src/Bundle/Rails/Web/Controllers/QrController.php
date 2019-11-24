<?php

namespace App\Bundle\Rails\Web\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QrController extends AbstractController
{

    protected $layoutRender = 'layout/main';

    public function actionIndex()
    {
        $qr = \php7extension\core\qr\helpers\QrHelper::encode('123456', []);
        //return $this->render('qr/index', ['qr' => $qr]);
    }

}