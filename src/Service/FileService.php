<?php

namespace App\Service;

use App\Repository\FileRepository;

class FileService
{
    private $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function checkIfIdExists(int $id): bool
    {
        return $this->fileRepository->idExists($id);
    }

    public function getRandomId(): int
    {
        $id = random_int(1, 100000);
        while ($this->checkIfIdExists($id)) {
            $id = random_int(1, 100000);
        }
        return $id;
    }
}