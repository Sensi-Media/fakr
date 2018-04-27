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
        $this->sensiAdapter->insertInto('fakr_inbox')
            ->execute([
                'sender' => $msg->getFrom(),
                'recipient' => $msg->getTo(),
                'subject' => $msg->getSubject(),
                'body' => "$msg",
            ]);
        return true;
    }
}

