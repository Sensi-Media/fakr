<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Injector;
use Swift_Mime_SimpleMessage;

class Mailer
{
    use Injector;

    private $sensiAdapter;
    
    public function __construct()
    {
        $this->inject(function ($sensiAdapter) {});
    }
    
    public function send(Swift_Mime_SimpleMessage $msg) : bool
    {
        $sender = $this->normalize($msg->getFrom());
        $recipient = $this->normalize($msg->getTo());
        $subject = $msg->getSubject();
        $body = "$msg";
        $this->sensiAdapter->insertInto('fakr_inbox')
            ->execute(compact('sender', 'recipient', 'subject', 'body'));
        return true;
    }
}

