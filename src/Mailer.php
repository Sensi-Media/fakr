<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Injector;
use Symfony\Component\Mailer\{ Mailer as BaseMailer, Transport };
use Symfony\Component\Mime\Email;
use DomainException;
use Monolyth\Envy\Environment;

class Mailer extends BaseMailer
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

