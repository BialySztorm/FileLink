<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Entity\File;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $fileData;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->clients = new \SplObjectStorage();
        $this->fileData = new \SplObjectStorage();
        $this->entityManager = $entityManager;
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
            // TODO get correct metadata
            $response = '{"ok":"true","message":"Metadata received","id":"123","ownerToken":"abc","url":"localhost/file/123"}';
            $from->send($response);
        } else {
            // Handle file data
            if (isset($this->fileData[$from])) {
                $fileInfo = $this->fileData[$from];
                if ($msg === "\0") { // EOF signal
                    // Save the file data
//                    error_log('Saving ' . strlen($fileInfo['buffer']) . ' bytes');
                    $file = new File();
                    $file->setMetadata($fileInfo['metadata']);
                    $file->setData($fileInfo['buffer']);
                    $this->entityManager->persist($file);
                    $this->entityManager->flush();
                    $response = '{"ok":"true","message":"File data received"}';
                    $from->send($response);
                    unset($this->fileData[$from]);
                } else {
                    // Append data to buffer
//                    error_log('Received ' . strlen($msg) . ' bytes');
                    $fileInfo['buffer'] .= $msg;
                    $this->fileData[$from] = $fileInfo;
                }
            } else {
                error_log('Metadata not received');
                $from->send('{"error":"Metadata not received"}');
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
        $conn->send('{"error":"' . str_replace('"', "'", str_replace("\\", "/", $e->getMessage())) . '"}');
        $conn->close();
    }
}