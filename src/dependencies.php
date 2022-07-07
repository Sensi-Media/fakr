<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Container;
use Monolyth\Envy\Environment;
use Quibble\Postgresql\Adapter;
use Quibble\Query\Buildable;
use PDO;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\{ Mailer as BaseMailer, MailerInterface };
use Toast\Cache\Cache;

$container = new Container;
$env = $container->get('env');
$transport = $container->get('transport');

if ($env->dev && !$env->test) {
    $container->register(function (&$mailer) use ($transport) {
        $mailer = new Mailer($transport);
    });
} elseif ($env->test) {
    $container->register(function (&$mailer) use ($transport) {
        $mailer = new class($transport) implements MailerInterface {
            public function send(Email $msg, &$failedRecipients = null) : bool
            {
                $pool = Cache::getInstance(sys_get_temp_dir().'/fakr.cache');
                if (!(preg_match('/To: .*? <(.*?)>/m', "$msg", $to))) {
                    preg_match('/To: (.*?)$/m', "$msg", $to);
                }
                $pool->set($to[1], $msg);
                return true;
            }
        };
    });
} else {
    $container->register(function (&$mailer) use ($transport) {
        $mailer = new BaseMailer($transport);
    });
}

