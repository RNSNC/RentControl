<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $counterpartyNew;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $summaDok;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $summaZalog;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $summaArenda;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateClose;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $documentId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $documentNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity=Storage::class, inversedBy="documents")
     */
    private $storage;

    /**
     * @ORM\OneToMany(targetEntity=Rent::class, mappedBy="document")
     */
    private $rent;

    /**
     * @ORM\ManyToOne(targetEntity=Counterparty::class, inversedBy="documents")
     */
    private $counterparty;

    /**
     * @ORM\ManyToOne(targetEntity=Subdivision::class, inversedBy="documents")
     */
    private $subdivision;

    public function __construct()
    {
        $this->rent = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->documentId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function isCounterpartyNew(): ?bool
    {
        return $this->counterpartyNew;
    }

    public function setCounterpartyNew(?bool $counterpartyNew): self
    {
        $this->counterpartyNew = $counterpartyNew;

        return $this;
    }

    public function getSummaDok(): ?string
    {
        return $this->summaDok;
    }

    public function setSummaDok(?string $summaDok): self
    {
        $this->summaDok = $summaDok;

        return $this;
    }

    public function getSummaZalog(): ?string
    {
        return $this->summaZalog;
    }

    public function setSummaZalog(?string $summaZalog): self
    {
        $this->summaZalog = $summaZalog;

        return $this;
    }

    public function getSummaArenda(): ?string
    {
        return $this->summaArenda;
    }

    public function setSummaArenda(?string $summaArenda): self
    {
        $this->summaArenda = $summaArenda;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->dateCreate;
    }

    public function setDateCreate(?\DateTimeInterface $dateCreate): self
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    public function getDateClose(): ?\DateTimeInterface
    {
        return $this->dateClose;
    }

    public function setDateClose(?\DateTimeInterface $dateClose): self
    {
        $this->dateClose = $dateClose;

        return $this;
    }

    public function getDocumentId(): ?string
    {
        return $this->documentId;
    }

    public function setDocumentId(string $documentId): self
    {
        $this->documentId = $documentId;

        return $this;
    }

    public function getDocumentNumber(): ?string
    {
        return $this->documentNumber;
    }

    public function setDocumentNumber(?string $documentNumber): self
    {
        $this->documentNumber = $documentNumber;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCounterparty(): ?Counterparty
    {
        return $this->counterparty;
    }

    public function setCounterparty(?Counterparty $counterparty): self
    {
        $this->counterparty = $counterparty;

        return $this;
    }

    public function getStorage(): ?Storage
    {
        return $this->storage;
    }

    public function setStorage(?Storage $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function getRent(): ?Collection
    {
        return $this->rent;
    }

    public function getRentCount(): ?int
    {
        return count($this->rent);
    }

    public function addRent(Rent $rent): self
    {
        if (!$this->rent->contains($rent)) {
            $this->rent[] = $rent;
            $rent->setDocument($this);
        }

        return $this;
    }

    public function removeRent(Rent $rent): self
    {
        if ($this->rent->removeElement($rent)) {
            if ($rent->getDocument() === $this) {
                $rent->setDocument(null);
            }
        }

        return $this;
    }

    public function getSubdivision(): ?Subdivision
    {
        return $this->subdivision;
    }

    public function setSubdivision(?Subdivision $subdivision): self
    {
        $this->subdivision = $subdivision;

        return $this;
    }
}
