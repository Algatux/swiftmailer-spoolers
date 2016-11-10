<?php

declare(strict_types = 1);

namespace Algatux\Swiftmailer\Spoolers\Spooler\Mongo;

use Swift_Mime_Message;

/**
 * Class SwiftMessageDocumentMapper
 */
class SwiftMessageDocumentMapper
{
    /**
     * @var Swift_Mime_Message
     */
    private $message;

    /**
     * SwiftMessageMapper constructor.
     *
     * @param Swift_Mime_Message $message
     */
    public function __construct(Swift_Mime_Message $message)
    {
        $this->message = $message;
    }

    /**
     * @return \stdClass
     */
    public function toDocument(): \stdClass
    {
        $document = new \stdClass();
        $document->subject = $this->message->getSubject();
        $document->to = $this->message->getTo();
        $document->from = $this->message->getFrom();
        $document->cc = $this->message->getCc();
        $document->bcc = $this->message->getBcc();
        $document->body = $this->message->getBody();

        $document->sent = false;
        $document->attempt = 0;
        $document->sending = false;

        return $document;
    }
}