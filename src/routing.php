<?php

namespace Sensi\Fakr;

use Zend\Diactoros\RedirectResponse;

$router
    ->when('/', 'fakr-inbox')
    ->get(View::class)
    ->post(function (callable $GET) use ($router) {
        $controller = new Controller;
        foreach ($_POST['id'] as $id) {
            $controller->delete($id); 
        }
        return new RedirectResponse($router->generate('fakr-inbox'));
    });
$router->when("/(?'id'\d+)/", 'fakr-message')->get(function (int $id) {
    return new Message\View($id);
});

