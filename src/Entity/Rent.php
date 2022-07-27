<?php

namespace App\Entity;

use App\Repository\RentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RentRepository::class)
 */
class Rent
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
    private $klw;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stavka;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zalog;

    /**
     * @ORM\ManyToOne(targetEntity=InstrumentName::class, inversedBy="rents")
     */
    private $instrumentName;

    /**
     * @ORM\ManyToOne(targetEntity=Document::class, inversedBy="rent")
     */
    private $document;

    public function __toString()
    {
        if ($this->instrumentName)
        {
            $name = $this->instrumentName->getName();
            $group = $this->instrumentName->getInstrumentGroup()->getName();
            return "$group // $name";
        }
        else return $this->stavka;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKlw(): ?string
    {
        return $this->klw;
    }

    public function setKlw(?string $klw): self
    {
        $this->klw = $klw;

        return $this;
    }

    public function getStavka(): ?string
    {
        return $this->stavka;
    }

    public function setStavka(?string $stavka): self
    {
        $this->stavka = $stavka;

        return $this;
    }

    public function getZalog(): ?string
    {
        return $this->zalog;
    }

    public function setZalog(?string $zalog): self
    {
        $this->zalog = $zalog;

        return $this;
    }

    public function getInstrumentName(): ?InstrumentName
    {
        return $this->instrumentName;
    }

    public function setInstrumentName(?InstrumentName $instrumentName): self
    {
        $this->instrumentName = $instrumentName;

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }
}
