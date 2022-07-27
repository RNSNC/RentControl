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
     * @ORM\ManyToMany(targetEntity=Storage::class, inversedBy="documents")
     */
    private $storage;

    /**
     * @ORM\ManyToMany(targetEntity=Counterparty::class, inversedBy="documents")
     */
    private $counterparties;

    /**
     * @ORM\OneToMany(targetEntity=Rent::class, mappedBy="document")
     */
    private $rent;

    public function __construct()
    {
        $this->storage = new ArrayCollection();
        $this->counterparties = new ArrayCollection();
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

    /**
     * @return Collection<int, Storage>
     */
    public function getStorage(): Collection
    {
        return $this->storage;
    }

    public function addStorage(Storage $storage): self
    {
        if (!$this->storage->contains($storage)) {
            $this->storage[] = $storage;
        }

        return $this;
    }

    public function removeStorage(Storage $storage): self
    {
        $this->storage->removeElement($storage);

        return $this;
    }

    /**
     * @return Collection<int, Counterparty>
     */
    public function getCounterparties(): Collection
    {
        return $this->counterparties;
    }

    public function addCounterparty(Counterparty $counterparty): self
    {
        if (!$this->counterparties->contains($counterparty)) {
            $this->counterparties[] = $counterparty;
        }

        return $this;
    }

    public function removeCounterparty(Counterparty $counterparty): self
    {
        $this->counterparties->removeElement($counterparty);

        return $this;
    }

    /**
     * @return Collection<int, Rent>
     */
    public function getRent(): Collection
    {
        return $this->rent;
    }

    public function addRent(Rent $rent): self
    {
        if (!$this->rent->contains($rent)) {
            $this->rent[] = $rent;
            $rent->setDocument($this);
        }

        return $this;
    }

    public function getRentCount(): int
    {
        return count($this->rent);
    }

    public function removeRent(Rent $rent): self
    {
        if ($this->rent->removeElement($rent)) {
            // set the owning side to null (unless already changed)
            if ($rent->getDocument() === $this) {
                $rent->setDocument(null);
            }
        }

        return $this;
    }
}
