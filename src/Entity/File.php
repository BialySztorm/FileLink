<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'files')]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private int $downloads = 1;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $expirationDate;

    public function __construct()
    {
        $this->expirationDate = (new \DateTime())->modify('+1 day');
    }


    // Add other properties and methods as needed
    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setDownloads(int $downloads)
    {
        $this->downloads = $downloads;
    }

    public function setExpirationDate(\DateTime $expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDownloads(): int
    {
        return $this->downloads;
    }

    public function getExpirationDate(): \DateTime
    {
        return $this->expirationDate;
    }
}