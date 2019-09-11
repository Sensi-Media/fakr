<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Injector;
use Swift_Message;
use Swift_Mailer;
use Swift_SmtpTransport;
use DomainException;

class Mailer
{
    use Injector;

    /** @var Monolyth\Envy\Environment */
    private $env;
    
    public function __construct()
    {
        $transport = new Swift_SmtpTransport('localhost', 25);
        parent::__construct($transport);
        $this->inject(function ($env) {});
    }
    
    public function send(Swift_Message $msg) : bool
    {
        $msg->setTo($this->env->mail);
        return parent::send($msg);
    }
}

