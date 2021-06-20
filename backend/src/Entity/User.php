<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $opaque_id;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $pubsub_listen = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $pubsub_send = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $broadcaster_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $display_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profile_image_url;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpaqueId(): ?string
    {
        return $this->opaque_id;
    }

    public function setOpaqueId(string $opaque_id): self
    {
        $this->opaque_id = $opaque_id;

        return $this;
    }

    public function getPubsubListen(): ?array
    {
        return $this->pubsub_listen;
    }

    public function setPubsubListen(?array $pubsub_listen): self
    {
        $this->pubsub_listen = $pubsub_listen;

        return $this;
    }

    public function getPubsubSend(): ?array
    {
        return $this->pubsub_send;
    }

    public function setPubsubSend(?array $pubsub_send): self
    {
        $this->pubsub_send = $pubsub_send;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->user_id;
    }

    public function setUserId(?string $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getBroadcasterType(): ?string
    {
        return $this->broadcaster_type;
    }

    public function setBroadcasterType(string $broadcaster_type): self
    {
        $this->broadcaster_type = $broadcaster_type;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->display_name;
    }

    public function setDisplayName(?string $display_name): self
    {
        $this->display_name = $display_name;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getProfileImageUrl(): ?string
    {
        return $this->profile_image_url;
    }

    public function setProfileImageUrl(?string $profile_image_url): self
    {
        $this->profile_image_url = $profile_image_url;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
