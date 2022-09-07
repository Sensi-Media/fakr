<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Injector;
use Symfony\Component\Mailer\{ MailerInterface, Transport\TransportInterface, Envelope };
use Symfony\Component\Mime\RawMessage;
use DomainException;
use Monolyth\Envy\Environment;

class Mailer implements MailerInterface
{
    use Injector;

    private Environment $env;

    private TransportInterface $transport;
    
    public function __construct(TransportInterface $transport)
    {
        $this->inject(function ($env) {});
        $this->transport = $transport;
    }
    
    public function send(RawMessage $msg, ?Envelope $envelope = null) : void
    {
        $msg->to($this->env->email);
        $this->transport->send($msg, $envelope);
    }
}

