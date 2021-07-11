<?php

namespace App\Entity;

use App\Repository\RoverlayAppRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoverlayAppRepository::class)
 */
class RoverlayApp
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ext_client_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ext_client_secret;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $api_client_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $api_client_secret;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $api_access_token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $api_refresh_token;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $scopes = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $api_expires_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $api_last_auth;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExtClientId(): ?string
    {
        return $this->ext_client_id;
    }

    public function setExtClientId(string $ext_client_id): self
    {
        $this->ext_client_id = $ext_client_id;

        return $this;
    }

    public function getExtClientSecret(): ?string
    {
        return $this->ext_client_secret;
    }

    public function setExtClientSecret(string $ext_client_secret): self
    {
        $this->ext_client_secret = $ext_client_secret;

        return $this;
    }

    public function getApiClientId(): ?string
    {
        return $this->api_client_id;
    }

    public function setApiClientId(int $api_client_id): self
    {
        $this->api_client_id = $api_client_id;

        return $this;
    }

    public function getApiClientSecret(): ?string
    {
        return $this->api_client_secret;
    }

    public function setApiClientSecret(string $api_client_secret): self
    {
        $this->api_client_secret = $api_client_secret;

        return $this;
    }

    public function getApiAccessToken(): ?string
    {
        return $this->api_access_token;
    }

    public function setApiAccessToken(?string $api_access_token): self
    {
        $this->api_access_token = $api_access_token;

        return $this;
    }

    public function getApiRefreshToken(): ?string
    {
        return $this->api_refresh_token;
    }

    public function setApiRefreshToken(?string $api_refresh_token): self
    {
        $this->api_refresh_token = $api_refresh_token;

        return $this;
    }

    public function getScopes(): ?array
    {
        return $this->scopes;
    }

    public function setScopes(?array $scopes): self
    {
        $this->scopes = $scopes;

        return $this;
    }

    public function getApiExpiresAt(): ?\DateTimeInterface
    {
        return $this->api_expires_at;
    }

    public function setApiExpiresAt(?\DateTimeInterface $api_expires_at): self
    {
        $this->api_expires_at = $api_expires_at;

        return $this;
    }

    public function getApiLastAuth(): ?\DateTimeInterface
    {
        return $this->api_last_auth;
    }

    public function setApiLastAuth(?\DateTimeInterface $api_last_auth): self
    {
        $this->api_last_auth = $api_last_auth;

        return $this;
    }
}
