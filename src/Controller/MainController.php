<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class MainController
{
    public function index()
    {
        return new Response(
            '
<html>
<body>
<h1>Main page</h1>
<ul>
    <li><a href="/lucky/number">Fist controller</a></li>
    <li><a href="/auth">API - auth</a></li>
    <li><a href="/api/rbac">API - rbac</a></li>
    <li><a href="/rails">rails</a></li>
</ul>
</body>
</html>'
        );
    }

}