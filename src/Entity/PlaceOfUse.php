<?php

namespace App\Entity;

use App\Repository\PlaceOfUseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlaceOfUseRepository::class)
 */
class PlaceOfUse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idRentPlace;

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdRentPlace(): ?string
    {
        return $this->idRentPlace;
    }

    public function setIdRentPlace(?string $idRentPlace): self
    {
        $this->idRentPlace = $idRentPlace;

        return $this;
    }
}
