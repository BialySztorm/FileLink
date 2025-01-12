<?php

namespace App\Controller;

use App\Entity\File;
use App\Repository\FileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(FileRepository $fileRepository): Response
    {
        $files = $fileRepository->findAll();
        return $this->render('admin/index.html.twig', ['files' => $files]);
    }

    #[Route('/admin/delete/{id}', name: 'admin_delete_file')]
    public function delete(File $file, EntityManagerInterface $em): Response
    {
        $em->remove($file);
        $em->flush();
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/admin/upload', name: 'admin_upload_file', methods: ['POST'])]
    public function upload(Request $request, EntityManagerInterface $em): Response
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        if ($uploadedFile) {
            $file = new File();
            $file->setName($uploadedFile->getClientOriginalName());
            $file->setPath('/uploads/' . $uploadedFile->getClientOriginalName());
            $uploadedFile->move($this->getParameter('uploads_directory'), $uploadedFile->getClientOriginalName());
            $em->persist($file);
            $em->flush();
        }
        return $this->redirectToRoute('admin_dashboard');
    }
}