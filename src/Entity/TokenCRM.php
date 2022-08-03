<?php

namespace App\Entity;

use App\Repository\TokenCRMRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TokenCRMRepository::class)
 */
class TokenCRM
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
    private $tokenType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $expiresIn;

    /**
     * @ORM\Column(type="string", length=3000, nullable=true)
     */
    private $accessToken;

    /**
     * @ORM\Column(type="string", length=3000, nullable=true)
     */
    private $refreshToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateAccessToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRefreshToken;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTokenType()
    {
        return $this->tokenType;
    }

    public function setTokenType($tokenType): self
    {
        $this->tokenType = $tokenType;

        return $this;
    }

    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    public function setExpiresIn($expiresIn): self
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken($accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    public function setRefreshToken($refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getDateAccessToken(): ?\DateTimeInterface
    {
        return $this->dateAccessToken;
    }

    public function setDateAccessToken(?\DateTimeInterface $dateAccessToken): self
    {
        $this->dateAccessToken = $dateAccessToken;

        return $this;
    }

    public function getDateRefreshToken(): ?\DateTimeInterface
    {
        return $this->dateRefreshToken;
    }

    public function setDateRefreshToken(?\DateTimeInterface $dateRefreshToken): self
    {
        $this->dateRefreshToken = $dateRefreshToken;

        return $this;
    }
}
