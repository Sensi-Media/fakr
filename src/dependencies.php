<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Container;
use Monolyth\Envy\Environment;
use Quibble\Postgresql\Adapter;
use Quibble\Query\Buildable;
use PDO;

$container = new Container;

$container->register(function (&$fakrRepository) {
    $fakrRepository = new Repository;
});
$container->register(function (&$sensiAdapter) {
    $env = new Environment(dirname(__DIR__).'/Envy.json', function ($env) {
        return ['fakr'];
    });
    $sensiAdapter = new class(
        'host=jerom;dbname='.$env->db['name'],
        $env->db['user'],
        $env->db['pass'],
        [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]
    ) extends Adapter {
        use Buildable;
    };
});

