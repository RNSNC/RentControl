<?php

namespace App\Entity;

use App\Repository\CounterpartyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CounterpartyRepository::class)
 */
class Counterparty
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
    private $idCounterparty;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titleFull;

    /**
     * @ORM\Column(type="string",length=255, nullable=true)
     */
    private $inn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typePerson;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $patronymic;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressHome;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreate;

    /**
     * @ORM\OneToMany(targetEntity=Phone::class, mappedBy="counterparty")
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="counterparty")
     */
    private $documents;

    public function __construct()
    {
        $this->phone = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCounterparty(): ?string
    {
        return $this->idCounterparty;
    }

    public function setIdCounterparty(?string $idCounterparty): self
    {
        $this->idCounterparty = $idCounterparty;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitleFull(): ?string
    {
        return $this->titleFull;
    }

    public function setTitleFull(?string $titleFull): self
    {
        $this->titleFull = $titleFull;

        return $this;
    }

    public function getInn(): ?string
    {
        return $this->inn;
    }

    public function setInn(?string $inn): self
    {
        $this->inn = $inn;

        return $this;
    }

    public function getTypePerson(): ?string
    {
        return $this->typePerson;
    }

    public function setTypePerson(?string $typePerson): self
    {
        $this->typePerson = $typePerson;

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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getAddressHome(): ?string
    {
        return $this->addressHome;
    }

    public function setAddressHome(string $addressHome): self
    {
        $this->addressHome = $addressHome;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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

    /**
     * @return Collection<int, Phone>
     */
    public function getPhone(): Collection
    {
        return $this->phone;
    }

    public function addPhone(Phone $phone): self
    {
        if (!$this->phone->contains($phone)) {
            $this->phone[] = $phone;
            $phone->setCounterparty($this);
        }

        return $this;
    }

    public function removePhone(Phone $phone): self
    {
        if ($this->phone->removeElement($phone)) {
            if ($phone->getCounterparty() === $this) {
                $phone->setCounterparty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setCounterparty($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            if ($document->getCounterparty() === $this) {
                $document->setCounterparty(null);
            }
        }

        return $this;
    }
}
