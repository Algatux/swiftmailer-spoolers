<?php

declare(strict_types=1);

namespace Algatux\SwiftmailerSpoolers\Mongo;

use Algatux\Swiftmailer\Spoolers\Spooler\Mongo\DocumentSwiftMessageMapper;
use Algatux\Swiftmailer\Spoolers\Spooler\Mongo\SwiftMessageMapper;
use MongoDB\Collection;
use MongoDB\Driver\Cursor;
use Swift_ConfigurableSpool;
use Swift_Message;
use Swift_Mime_Message;
use Swift_Transport;

/**
 * Class MongoSpooler
 */
class MongoSpooler extends Swift_ConfigurableSpool
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * MongoSpooler constructor.
     *
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Starts this Spool mechanism.
     */
    public function start()
    {
        // do nothing
    }

    /**
     * Stops this Spool mechanism.
     */
    public function stop()
    {
        // do nothing
    }

    /**
     * Tests if this Spool mechanism has started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * Queues a message.
     *
     * @param Swift_Mime_Message $message The message to store
     *
     * @return bool Whether the operation has succeeded
     */
    public function queueMessage(Swift_Mime_Message $message)
    {
        $mapper = new SwiftMessageMapper($message);

        $this
            ->collection
            ->insertOne($mapper->toDocument());
    }

    /**
     * Sends messages using the given transport instance.
     *
     * @param Swift_Transport $transport        A transport instance
     * @param string[]        $failedRecipients An array of failures by-reference
     *
     * @return int The number of sent emails
     */
    public function flushQueue(Swift_Transport $transport, &$failedRecipients = null)
    {
        $sentEmailsNumber = 0;
        $queue = $this->retrieveQueuedMessages();

        foreach ($queue as $messageDocument) {

            $mapper = new DocumentSwiftMessageMapper($messageDocument);

            $actualSentNumber = $transport->send(
                $mapper->toSwiftMessage(),
                $failedRecipients
            );

            if (1 > $actualSentNumber) {
                $sentEmailsNumber += $actualSentNumber;

                // mark failed for retry (and increment attempt ?)

                continue;
            }

            // mark sent
        }

        return $sentEmailsNumber;
    }

    /**
     * @return object[]
     */
    private function retrieveQueuedMessages(): array
    {
        $cursor = $this
            ->collection
            ->find(
                [
                    'sent' => false,
                    'sending' => false,
                    'attempts' => ['$lte' => 5],
                ]
            );

        return $cursor->toArray();
    }
}