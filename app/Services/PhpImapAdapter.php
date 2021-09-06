<?php

namespace App\Services;

use Webklex\PHPIMAP\ClientManager;
use Illuminate\Support\Facades\File;
use App\Services\Contracts\ImapAdapter;
use Illuminate\Support\Facades\Storage;

class PhpImapAdapter implements ImapAdapter
{
    protected $client;

    public function __construct($imapAccount = 'default')
    {
        $this->client = (new ClientManager(base_path('/config/imap.php')))
            ->account($imapAccount);
    }

    /**
     * Return all inbox messages
     */
    public function getInboxMessages($page = null, $inboxPath = 'INBOX')
    {
        $this->connect();

        //Get INBOX Mailboxes
        $mailFolder = $this->getFolder($inboxPath);

        //Get all Messages of the current Mailbox $mailFolders
        $mailMessages = $mailFolder->query()
            ->all()
            ->setFetchBody(false)
            ->limit(100)
            ->paginate(10, $page, 'page');

        $this->disconnect();

        $formated = $mailMessages->map(function ($message) {
            return [
                'date' => $message->getDate()[0]->format('Y-m-d H:m:s'),
                'uid' => $message->getUid(),
                'from' => $this->decodeHeader($message->getFrom()[0]->full),
                'subject' => str_replace('_', ' ', $this->decodeHeader($message->getSubject()[0])),
                'flags' => $message->flags,
            ];
        });

        return [$formated, $mailMessages->links()];
    }

    public function writeMessage($message)
    {
        # code...
    }

    public function showMessage($messageId, $inboxPath = 'INBOX')
    {
        //Get INBOX Mailboxes
        $mailFolder = $this->getFolder($inboxPath);
        $message = $mailFolder->query()->markAsRead()->getMessageByUid($messageId);
        $attachments = collect();

        if ($message->hasAttachments()) {
            foreach ($message->getAttachments() as $attachment) {
                $filePath = $this->getAttachmentsFolder();
                $attachment->save($filePath);

                $attachments->push([
                    'id' => $attachment->get('id'),
                    'name' => $attachment->getName(),
                    'mime' => $attachment->getMimeType(),
                    'content_type' => $attachment->get('content_type'),
                    'img_src' => '/attachments/' . $attachment->getName(),
                    'type' => $attachment->get('type'),
                    'extension' => $attachment->getExtension(),
                    'size' => $attachment->get('size'),
                ]);
            }
        }

        return [
            'from' => [
                'personal' => $this->decodeHeader($message->getFrom()[0]->personal),
                'mailbox' => $message->getFrom()[0]->mailbox,
                'host' => $message->getFrom()[0]->host,
                'mail' => $message->getFrom()[0]->mail,
                'full' => $this->decodeHeader($message->getFrom()[0]->full),
            ],
            'date' => $message->getDate()[0]->format('Y-m-d H:m:s'),
            'uid' => $message->getUid(),
            'subject' => str_replace('_', ' ', $this->decodeHeader($message->getSubject()[0])),
            'body' => $message->getHTMLBody(true) ?? $message->getTextBody(),
            'body_plain' => $message->getTextBody(),
            'flags' => $message->flags,
            'attachments' => $attachments,
        ];
    }

    public function moveMessage($messageId)
    {
        # code...
    }

    public function deleteMessage($messageId)
    {
        # code...
    }

    /**
     * @return \Webklex\PHPIMAP\Folder
     */
    private function getFolder($inboxPath)
    {
        return $this->client->getFolder($inboxPath);
    }

    private function decodeHeader($string)
    {
        mb_internal_encoding('UTF-8');

        if (mb_detect_encoding($string) == 'ASCII') {
            return iconv_mime_decode($string, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF-8');
        } else {
            return $string;
        }
    }

    private function getAttachmentsFolder()
    {
        $attachmentsPath = storage_path('app/public/attachments/');
        if (!File::exists($attachmentsPath)) {
            File::makeDirectory($attachmentsPath);
        }
        return $attachmentsPath;
    }

    private function connect()
    {
        $this->client->connect();
    }

    private function reconnect()
    {
        $this->client->reconnect();
    }

    private function disconnect()
    {
        $this->client->disconnect();
    }
}