<?php

declare(strict_types = 1);

namespace Algatux\Swiftmailer\Spoolers\Spooler\Mongo;

use Swift_Mime_Message;

/**
 * Class DocumentSwiftMessageMapper
 */
class DocumentSwiftMessageMapper
{
    /**
     * @var object
     */
    private $document;

    /**
     * SwiftMessageMapper constructor.
     *
     * @param object $document
     */
    public function __construct(object $document)
    {
        $this->document = $document;
    }

    /**
     * @return \Swift_Mime_Message
     */
    public function toSwiftMessage(): Swift_Mime_Message
    {
        $message = new \Swift_Message();
        $message->setSubject($this->document->subject);
        $message->setTo($this->document->to);
        $message->setFrom($this->document->from);
        $message->setCc($this->document->cc);
        $message->setBcc($this->document->bcc);
        $message->setBody($this->document->body);

        return $message;
    }
}