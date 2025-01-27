<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendController extends AbstractController
{
    #[Route('/__version__', name: 'version', methods: ['GET'])]
    public function version(): JsonResponse
    {
        return new JsonResponse(['version' => 'v3.1.0']);
    }

    #[Route('/api/ws', name: 'ws', methods: ['GET'])]
    public function websocket(): RedirectResponse
    {
        error_log('Redirecting to ws://');
        return new RedirectResponse('ws://localhost:8081/api/ws');
    }

    #[Route('/api/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(int $id): JsonResponse
    {
        // TODO: Implement file deletion
        return new JsonResponse(['message' => 'File deleted']);
    }

    #[Route('/api/password/{id}', name: 'password', methods: ['POST'])]
    public function password(int $id): JsonResponse
    {
        // TODO: Implement password protection
        return new JsonResponse(['message' => 'Password set']);
    }

    #[Route('/file/{id}', name: 'file_page', methods: ['GET'], requirements: ['id' => '\d+', 'rawsecret' => '[\w-]+'])]
    public function filePage(int $id): Response
    {
        return $this->render('file.html.twig', [
            'id' => $id,
        ]);
    }
    #[Route('/icon.718f87fb.svg', name: 'icon_svg', methods: ['GET'])]
    public function iconSvg(): BinaryFileResponse
    {
        $filePath = $this->getParameter('kernel.project_dir') . '/public/icon.718f87fb.svg';
        return new BinaryFileResponse($filePath);
    }
}