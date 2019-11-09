<?php

namespace App\Bundle\Rails\Web\Controller;

use php7extension\bundle\geo\domain\entities\CityEntity;
use php7extension\yii\db\Query;
use php7rails\domain\exceptions\UnprocessableEntityHttpException;
use php7rails\domain\helpers\DomainHelper;
use php7rails\domain\helpers\factory\RepositoryFactoryHelper;
use php7rails\domain\helpers\factory\ServiceFactoryHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SandboxController extends AbstractController
{

    protected $layoutRender = 'layout/main';

    public function actionIndex()
    {

        //new \yubundle\bundle\account\domain\v2\Domain;
        //$collection = \App::$domain->db->main->getQueryBuilder()

        return $this->render('sandbox/index');
    }

    public function actionAuth()
    {
        $identity = \App::$domain->account->auth->authentication('admin', 'Wwwqqq111');
        $identity = \App::$domain->account->auth->authenticationByToken($identity->token);
        dd($identity);
        //return $this->render('sandbox/index', ['data' => $identity]);
    }

    public function actionValidateEntity()
    {
        $city = new CityEntity;
        //$city->country_id = 1;
        $city->name = 'qwerty';
        try {
            $data = $city->validate();
        } catch (UnprocessableEntityHttpException $e) {
            $data = $e->getErrors();
        }
        dd($data);
        //return $this->render('sandbox/index', ['data' => $data]);
    }

    public function actionTestModel()
    {
        try {
            $data = \App::$domain->model->entity->validate(2, [
                'language' => 'ru____',
            ]);
        } catch (UnprocessableEntityHttpException $e) {
            $data = $e->getErrors();
        }
        dd($data);
        return $this->render('sandbox/index', ['data' => $data]);
    }

    public function actionQueryBuilder()
    {
        $collection = (new Query())
            ->select('*')
            ->from('user.person')
            //->where(['last_name' => 'Smith'])
            ->limit(10)
            ->all(\App::$domain->db->main);
        dd($collection);
        return $this->render('sandbox/index', ['data' => $collection]);
    }

    public function actionIndex2()
    {
        $domain = new \php7extension\bundle\model\domain\Domain;
        $domain->id = 'model';
        $domainConfig = $domain->config();

        $services = [];
        foreach ($domainConfig['services'] as $id => $def) {
            if (is_integer($id)) {
                $services[$def] = null;
            } else {
                $services[$id] = $def;
            }
        }

        $repositories = RepositoryFactoryHelper::genConfigs($domainConfig['repositories'], $domain);
        $services = ServiceFactoryHelper::genConfigs($services, $domain);

        return $this->render('sandbox/index');
    }

    public function actionRrrr()
    {
        $mm = DomainHelper::createDomain('model', 'php7extension\bundle\model\domain\Domain');
        $all = $mm->book->repository->all();
        return $this->render('sandbox/index', ['data' => $all]);
    }

}