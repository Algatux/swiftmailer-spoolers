<?php

declare(strict_types=1);

namespace Algatux\SwiftmailerSpoolers;

use MongoDB\Collection;
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
        $this
            ->collection
            ->insertOne([]);
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
        $message = new Swift_Message();
        $transport->send($message);
    }
}