<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Password;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
    public function delete(int $id, Request $request): JsonResponse
    {
        $file = $this->entityManager->getRepository(File::class)->find($id);

        if (!$file) {
            return new JsonResponse(['message' => 'File not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if($file->getToken() != $data['owner_token'])
        {
            return new JsonResponse(['message' => 'Invalid token'], 403);
        }

        $this->entityManager->remove($file);
        $this->entityManager->flush();

        $password = $this->entityManager->getRepository(Password::class)->findOneBy(['file_id' => $id]);

        if($password)
        {
            $this->entityManager->remove($password);
            $this->entityManager->flush();
        }

        return new JsonResponse(['message' => 'File deleted']);
    }

    #[Route('/api/password/{id}', name: 'password', methods: ['POST'])]
    public function password(int $id, Request $request): JsonResponse
    {
        $file = $this->entityManager->getRepository(File::class)->find($id);

        if (!$file) {
            return new JsonResponse(['message' => 'File not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if($file->getToken() != $data['owner_token'])
        {
            return new JsonResponse(['message' => 'Invalid token'], 403);
        }

        $password = new Password();
        $password->setPassword($data['auth']);
        $password->setFileId($id);
        $this->entityManager->persist($password);
        $this->entityManager->flush();
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