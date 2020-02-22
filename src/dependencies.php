<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Container;
use Monolyth\Envy\Environment;
use Quibble\Postgresql\Adapter;
use Quibble\Query\Buildable;
use PDO;
use Swift_Mime_SimpleMessage;
use Swift_Mailer;
use Toast\Cache\Cache;

$container = new Container;
$env = $container->get('env');
$transport = $container->get('transport');

if ($env->dev && !$env->test) {
    $container->register(function (&$mailer) use ($transport) {
        $mailer = new Mailer($transport);
    });
} elseif ($env->test) {
    class Mailer extends Swift_Mailer
    {
        public function send(Swift_Mime_SimpleMessage $msg, &$failedRecipients = null) : bool
        {
            $pool = Cache::getInstance(sys_get_temp_dir().'/fakr.cache');
            if (!(preg_match('/To: .*? <(.*?)>/m', "$msg", $to))) {
                preg_match('/To: (.*?)$/m', "$msg", $to);
            }
            $pool->set($to[1], $msg);
            return true;
        }
    }
    $container->register(function (&$mailer) use ($transport) {
        $mailer = new Mailer($transport);
    });
} else {
    $container->register(function (&$mailer) use ($transport) {
        $mailer = new Swift_Mailer($transport);
    });
}

