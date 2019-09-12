<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Injector;
use Swift_Mime_SimpleMessage;
use Swift_Mailer;
use Swift_SmtpTransport;
use DomainException;

class Mailer extends Swift_Mailer
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
    
    public function send(Swift_Mime_SimpleMessage $msg, &$failedRecipients = null)
    {
        $msg->setTo($this->env->email);
        return parent::send($msg);
    }
}

