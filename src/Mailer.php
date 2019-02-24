<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Injector;
use Swift_Message;
use DomainException;

class Mailer
{
    use Injector;

    private $sensiAdapter;
    
    public function __construct()
    {
        $this->inject(function ($sensiAdapter) {});
    }
    
    public function send(Swift_Message $msg) : bool
    {
        $sender = $this->normalize($msg->getFrom());
        $recipient = $this->normalize($msg->getTo());
        $subject = $msg->getSubject();
        $body = "$msg";
        $this->sensiAdapter->insertInto('fakr_inbox')
            ->execute(compact('sender', 'recipient', 'subject', 'body'));
        return true;
    }

    /**
     * Normalize an address. Swift gives these as either a string, or a hash of
     * email => realname pairs. Might also contain multiple addresses.
     *
     * @param string|array $address
     * @return string
     * @throws DomainException if something non-valid was passed.
     */
    private function normalize($address) : string
    {
        if (is_string($address)) {
            return $address;
        }
        if (is_array($address)) {
            $retval = [];
            foreach ($address as $email => $name) {
                if (is_numeric($email)) {
                    $retval[] = $name;
                    continue;
                }
                $retval[] = "$name <$email>";
            }
            return implode(', ', $retval);
        }
        throw new DomainException;
    }
}

