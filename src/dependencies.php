<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Container;
use Monolyth\Envy\Environment;
use Quibble\Postgresql\Adapter;
use Quibble\Query\Buildable;
use PDO;
use Swift_Message;
use Toast\Cache\Cache;

$container = new Container;
$env = $container->get('env');

if ($env->dev && !$env->test) {
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
    $container->register(function (&$mailer) {
        $mailer = new Mailer;
    });
} elseif ($env->test) {
    class Mailer
    {
        public function send(Swift_Message $msg) : bool
        {
            $pool = Cache::getInstance(sys_get_temp_dir().'/fakr.cache');
            if (!(preg_match('/To: .*? <(.*?)>/m', "$msg", $to))) {
                preg_match('/To: (.*?)$/m', "$msg", $to);
            }
            $pool->set($to[1], $msg);
            return true;
        }
    }
    $container->register(function (&$mailer) {
        $mailer = new Mailer;
    });
}

