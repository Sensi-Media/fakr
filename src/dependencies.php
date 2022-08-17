<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Container;
use Monolyth\Envy\Environment;
use Quibble\Postgresql\Adapter;
use Quibble\Query\Buildable;
use PDO;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mailer\{ Mailer as BaseMailer, MailerInterface, Envelope };
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
            public function send(RawMessage $msg, ?Envelope $envelope = null) : void
            {
                $pool = Cache::getInstance(sys_get_temp_dir().'/fakr.cache');
                if (!(preg_match('/To: .*? <(.*?)>/m', $msg->toString(), $to))) {
                    preg_match('/To: (.*?)$/m', $msg->toString(), $to);
                }
                $pool->set($to[1], $msg);
            }
        };
    });
} else {
    $container->register(function (&$mailer) use ($transport) {
        $mailer = new BaseMailer($transport);
    });
}

