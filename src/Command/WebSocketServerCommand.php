<?php

namespace App\Command;

use App\Service\FileService;
use App\Service\WebSocketServer;
use Doctrine\ORM\EntityManagerInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebSocketServerCommand extends Command
{
    protected static $defaultName = 'app:websocket-server';
    private $entityManager;
    private $fileService;

    public function __construct(EntityManagerInterface $entityManager, FileService $fileService)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->fileService = $fileService;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new WebSocketServer($this->entityManager, $this->fileService)
                )
            ),
            8080
        );

        $output->writeln('WebSocket server started on port 8080');
        $server->run();

        return Command::SUCCESS;
    }
}