<?php

namespace Tests\Bundle\Article\Controller;

use PhpLab\Test\BaseRestTest;
use php7extension\core\web\enums\HttpMethodEnum;
use php7extension\core\web\enums\HttpStatusCodeEnum;

class PostControllerPHPTest extends PostControllerTest
{

    protected $basePath = 'php/v1/';

    public function testBadCreate()
    {
        $this->assertEquals(1, 1);
    }

    public function testBadUpdate()
    {
        $this->assertEquals(1, 1);
    }

    public function testCreate()
    {
        $this->assertEquals(1, 1);
    }

    public function testMethodAllowed()
    {
        $this->assertEquals(1, 1);
    }

    public function testOptions()
    {
        $this->assertEquals(1, 1);
    }

}
