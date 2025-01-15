<?php

namespace App\Service;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $fileData;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
        $this->fileData = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        if (isset($data['fileMetadata'])) {
            // Handle metadata
//            error_log($msg);
            $this->fileData[$from] = [
                'metadata' => $data,
                'buffer' => ''
            ];
            $from->send('{"message":"Metadata received"}');
        } else {
            // Handle file data
            if (isset($this->fileData[$from])) {
                $fileInfo = $this->fileData[$from];
                if ($msg === "\0") { // EOF signal
                    // Save the file data
//                    error_log('Saving ' . strlen($fileInfo['buffer']) . ' bytes');
                    // TODO Save to database
                    $from->send('{"message":"File data received"}');
                    unset($this->fileData[$from]);
                } else {
                    // Append data to buffer
//                    error_log('Received ' . strlen($msg) . ' bytes');
                    $fileInfo['buffer'] .= $msg;
                    $this->fileData[$from] = $fileInfo;
                }
            } else {
                error_log('Metadata not received');
                $from->send('{"Error":"Metadata not received"}');
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        unset($this->fileData[$conn]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }
}