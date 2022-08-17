<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Injector;
use Symfony\Component\Mailer\{ MailerInterface, Transport };
use Symfony\Component\Mime\Email;
use DomainException;
use Monolyth\Envy\Environment;

class Mailer implements MailerInterface
{
    use Injector;

    private Environment $env;
    
    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->inject(function ($env) {});
    }
    
    public function send(Email $msg, &$failedRecipients = null) : bool
    {
        $msg->to($this->env->email);
        return parent::send($msg);
    }
}

