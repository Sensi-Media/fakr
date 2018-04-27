<?php

namespace Sensi\Fakr;

use Zend\Diactoros\RedirectResponse;

$router
    ->when('/', 'inbox')
    ->get(View::class)
    ->post(function (callable $GET) use ($router) {
        $controller = new Controller;
        foreach ($_POST['id'] as $id) {
            $controller->delete($id); 
        }
        return new RedirectResponse($router->generate('inbox'));
    });
$router->when("/(?'id'\d+)/", 'inbox-detail')->get(function (int $id) {
    return new Message\View($id);
});

