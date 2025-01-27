<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'passwords')]
class Password
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'text', length: 255)]
    private $password;

    #[ORM\Column(type: 'integer')]
    private $file_id;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFileId(): ?int
    {
        return $this->file_id;
    }

    public function setFileId($file_id): self
    {
        $this->file_id = $file_id;

        return $this;
    }

}