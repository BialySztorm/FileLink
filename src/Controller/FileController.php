<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
    #[Route('/__version__', name: 'filelink_version', methods: ['GET'])]
    public function version(): JsonResponse
    {
        return new JsonResponse(['version' => 'v3.1.0']);
    }
}