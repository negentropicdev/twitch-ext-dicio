<?php

namespace App\Entity;

use App\Repository\ChannelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChannelRepository::class)
 */
class Channel
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
    private $channel_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $channel_name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $approved;

    /**
     * @ORM\Column(type="boolean")
     */
    private $live;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=ControlSet::class, mappedBy="channel")
     */
    private $controlSets;

    public function __construct()
    {
        $this->controlSets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChannelId(): ?string
    {
        return $this->channel_id;
    }

    public function setChannelId(string $channel_id): self
    {
        $this->channel_id = $channel_id;

        return $this;
    }

    public function getChannelName(): ?string
    {
        return $this->channel_name;
    }

    public function setChannelName(string $channel_name): self
    {
        $this->channel_name = $channel_name;

        return $this;
    }

    public function getApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): self
    {
        $this->approved = $approved;

        return $this;
    }

    public function getLive(): ?bool
    {
        return $this->live;
    }

    public function setLive(bool $live): self
    {
        $this->live = $live;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|ControlSet[]
     */
    public function getControlSets(): Collection
    {
        return $this->controlSets;
    }

    public function addControlSet(ControlSet $controlSet): self
    {
        if (!$this->controlSets->contains($controlSet)) {
            $this->controlSets[] = $controlSet;
            $controlSet->setChannel($this);
        }

        return $this;
    }

    public function removeControlSet(ControlSet $controlSet): self
    {
        if ($this->controlSets->removeElement($controlSet)) {
            // set the owning side to null (unless already changed)
            if ($controlSet->getChannel() === $this) {
                $controlSet->setChannel(null);
            }
        }

        return $this;
    }
}
