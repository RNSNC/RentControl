<?php

namespace App\Entity;

use App\Repository\InstrumentGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InstrumentGroupRepository::class)
 */
class InstrumentGroup
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
     * @ORM\OneToMany(targetEntity=InstrumentName::class, mappedBy="instrumentGroup")
     */
    private $instrumentNames;

    public function __construct()
    {
        $this->instrumentNames = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, InstrumentName>
     */
    public function getInstrumentNames(): Collection
    {
        return $this->instrumentNames;
    }

    public function addInstrumentName(InstrumentName $instrumentName): self
    {
        if (!$this->instrumentNames->contains($instrumentName)) {
            $this->instrumentNames[] = $instrumentName;
            $instrumentName->setInstrumentGroup($this);
        }

        return $this;
    }

    public function removeInstrumentName(InstrumentName $instrumentName): self
    {
        if ($this->instrumentNames->removeElement($instrumentName)) {
            // set the owning side to null (unless already changed)
            if ($instrumentName->getInstrumentGroup() === $this) {
                $instrumentName->setInstrumentGroup(null);
            }
        }

        return $this;
    }
}
