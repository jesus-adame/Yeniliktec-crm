<?php

namespace App\Services\Contracts;

interface ImapAdapter
{
    /**
     * Return all inbox messages
     */
    public function getInboxMessages();

    /**
     * Write a message
     */
    public function writeMessage($message);

    public function showMessage($messageId);

    public function moveMessage($messageId);

    public function deleteMessage($messageId);
}