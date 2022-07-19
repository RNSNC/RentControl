<?php

namespace App\Entity;

use App\Repository\InstrumentNameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InstrumentNameRepository::class)
 */
class InstrumentName
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue]
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=InstrumentGroup::class,inversedBy="instrumentNames")
     */
    private $instrumentGroup;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idObjectRent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Rent::class, mappedBy="instrumentName")
     */
    private $rents;

    public function __construct()
    {
        $this->rents = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstrumentGroup(): ?InstrumentGroup
    {
        return $this->instrumentGroup;
    }

    public function setInstrumentGroup(?InstrumentGroup $instrumentGroup): self
    {
        $this->instrumentGroup = $instrumentGroup;

        return $this;
    }

    public function getIdObjectRent(): ?string
    {
        return $this->idObjectRent;
    }

    public function setIdObjectRent(?string $idObjectRent): self
    {
        $this->idObjectRent = $idObjectRent;

        return $this;
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

    /**
     * @return Collection<int, Rent>
     */
    public function getRents(): Collection
    {
        return $this->rents;
    }

    public function addRent(Rent $rent): self
    {
        if (!$this->rents->contains($rent)) {
            $this->rents[] = $rent;
            $rent->setInstrumentName($this);
        }

        return $this;
    }

    public function removeRent(Rent $rent): self
    {
        if ($this->rents->removeElement($rent)) {
            // set the owning side to null (unless already changed)
            if ($rent->getInstrumentName() === $this) {
                $rent->setInstrumentName(null);
            }
        }

        return $this;
    }
}
