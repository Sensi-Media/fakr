<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Container;
use Monolyth\Envy\Environment;
use Quibble\Postgresql\Adapter;
use Quibble\Query\Buildable;
use PDO;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_Mailer;
use Toast\Cache\Cache;

$container = new Container;
$env = $container->get('env');

if ($env->dev && !$env->test) {
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
} else {
    $container->register(function (&$mailer) {
        $transport = new Swift_SmtpTransport('localhost', 25);
        $mailer = new Swift_Mailer($transport);
    });
}

