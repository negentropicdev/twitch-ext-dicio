<?php

namespace App\Entity;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\UserRepository;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements \JsonSerializable
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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $access_token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refresh_token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expires_at;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $scopes = [];

    /**
     * @ORM\OneToMany(targetEntity=ControlGroup::class, mappedBy="dynamic_user")
     */
    private $controlGroups;

    public function __construct()
    {
        $this->controlGroups = new ArrayCollection();
    }

    public function jsonSerialize() {
      $res = [
        'id' => $this->id,
        'login' => $this->login,
        'display_name' => $this->display_name,
        'broadcaster_type' => $this->broadcaster_type,
        'type' => $this->type,
        'created_at' => ($this->created_at != null ? Carbon::instance($this->created_at, 'UTC')->toIso8601ZuluString() : null),
        'profile_image_url' => $this->profile_image_url,
        'opaque_id' => $this->opaque_id,
        'user_id' => $this->user_id,
        'listen' => $this->pubsub_listen,
        'send' => $this->pubsub_send
      ];

      return $res;
    }

    public function isIdentified(): bool
    {
      return $this->user_id != null;
    }

    public function hasLogin(): bool
    {
      return $this->login != null && $this->login != '';
    }

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

    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    public function setAccessToken(?string $access_token): self
    {
        $this->access_token = $access_token;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refresh_token;
    }

    public function setRefreshToken(?string $refresh_token): self
    {
        $this->refresh_token = $refresh_token;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expires_at;
    }

    public function setExpiresAt(?\DateTimeInterface $expires_at): self
    {
        $this->expires_at = $expires_at;

        return $this;
    }

    public function getScopes(): ?array
    {
        return $this->scopes;
    }

    public function setScopes(array $scopes): self
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * @return Collection|ControlGroup[]
     */
    public function getControlGroups(): Collection
    {
        return $this->controlGroups;
    }

    public function addControlGroup(ControlGroup $controlGroup): self
    {
        if (!$this->controlGroups->contains($controlGroup)) {
            $this->controlGroups[] = $controlGroup;
            $controlGroup->setDynamicUser($this);
        }

        return $this;
    }

    public function removeControlGroup(ControlGroup $controlGroup): self
    {
        if ($this->controlGroups->removeElement($controlGroup)) {
            // set the owning side to null (unless already changed)
            if ($controlGroup->getDynamicUser() === $this) {
                $controlGroup->setDynamicUser(null);
            }
        }

        return $this;
    }
}
