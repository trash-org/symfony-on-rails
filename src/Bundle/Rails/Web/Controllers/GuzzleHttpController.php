<?php

namespace App\Bundle\Rails\Web\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use php7extension\core\develop\helpers\Benchmark;
use php7extension\core\web\enums\HttpHeaderEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use function GuzzleHttp\Promise\settle;

class GuzzleHttpController extends AbstractController
{

    protected $layoutRender = 'layout/main';

    public function actionIndex()
    {
        $client = new Client;
        $host = \App::$domain->component->request->getSchemeAndHttpHost();
        //dd($host);
        $response = $client->request('GET', $host . '/rails/rbac/roles', [
            'headers' => [
                'Accept' => 'application/json',
                //'Content-type' => 'application/json',
            ],
        ]);
        $content = $this->getData($response);
        dd($content);
        return $this->render('default/index');
    }

    public function actionAuth()
    {
        $client = new Client;
        $response = $client->request('POST', 'http://api.union.project/v1/auth', [
            'form_params' => [
                'login' => 'admin',
                'password' => 'Wwwqqq111',
            ],
            'headers' => [
                'Accept' => 'application/json',
                //'Content-type' => 'application/json',
            ],
        ]);
        $content = $this->getData($response);
        dd($content);
        return $this->render('default/index');
    }

    public function actionStress()
    {
        $client = new Client;
        $options = [
            'headers' => [
                'Accept' => 'application/json',
                //'Content-type' => 'application/json',
                //'Authorization' => 'jwt eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6Ijg1ZDFmYzgwLTU0MzktNGYwOC1hYTM0LTUwM2ZkOWYzYWE4MSJ9.eyJzdWJqZWN0Ijp7ImlkIjoxfSwiYXVkIjpbXSwiZXhwIjoxNTY4MDMxNDM3fQ.6kXrJzS63OZXPS82R5pBpgHQqyJCPEYFjPtZqWnQA8g',
                //'Authorization' => 'jwt eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6ImZmY2QwYzJlLTQzYWItNDI2NS1jMmVhLWZlYzM4ZTFkNmRhMiJ9.eyJzdWJqZWN0Ijp7ImlkIjoxfSwiYXVkIjpbXSwiZXhwIjoxNTY4MDI5OTA3fQ.JxbMcEEL9tGDdmIa03Kq4kMqLmRnvmezWDao36Ctbps',
            ],
        ];
        $queryCount = 2;
        $totalCount = 2;
        //$url = 'http://test-api.union.yuwert.kz/v1/auth';
        //$url = 'https://api.search.yutest.kz/v1/auth';
        //$url = 'https://api.yunews.yutest.kz/v1/article';
        $url = 'http://test-api.union.yuwert.kz/v1/auth';
        //$url = 'http://api.union.project/v1/auth';

        $commonRuntime = 0;
        for ($k = 0; $k < $totalCount; $k++) {
            $promises = [];
            for ($i = 0; $i < $queryCount; $i++) {
                $promises['query_' . $i] = $client->getAsync($url, $options);
            }

            // Дождаться завершения всех запросов. Выдает исключение ConnectException если какой-либо из запросов не выполнен
            //$results = unwrap($promises);
            //d($results);

            // Дождемся завершения запросов, даже если некоторые из них завершатся неудачно
            Benchmark::begin('stress_test');
            $results = settle($promises)->wait();
            Benchmark::end('stress_test');
            $runtime = Benchmark::allFlat()['stress_test'];
            $commonRuntime = $commonRuntime + $runtime;
            /*foreach ($results as $result) {
                if($result['state'] != 'fulfilled' && $result['reason']['code'] > 500) {
                    d($result);
                    throw new \UnexpectedValueException('Error!!!!!!!!!!!!!!!');
                }
            }*/
        }

        $oneRuntime = $commonRuntime / ($queryCount * $totalCount);
        d([
            'commonRuntime' => $commonRuntime,
            'oneRuntime' => $oneRuntime,
            'queriesPerSecond' => round(1 / $oneRuntime),
        ]);

        //d($commonRuntime);
        //d($oneRuntime);
        //d($totalCount / $oneRuntime);
        dd($results);

        return $this->render('default/index');
    }

    private function getData(Response $response)
    {
        $contentType = $response->getHeaderLine(HttpHeaderEnum::CONTENT_TYPE);
        $contentTypeParts = HeaderUtils::split($contentType, ';=');
        $assoc = HeaderUtils::combine($contentTypeParts);
        $content = $response->getBody()->getContents();
        if ( ! empty($assoc['application/json'])) {
            $content = json_decode($content);
        }
        return $content;
    }

}