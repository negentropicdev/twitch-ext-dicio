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
     * @ORM\OneToMany(targetEntity=ControlGroup::class, mappedBy="channel", orphanRemoval=true)
     */
    private $controlGroups;

    /**
     * @ORM\OneToMany(targetEntity=GroupQueue::class, mappedBy="channel", orphanRemoval=true)
     */
    private $groupQueues;

    /**
     * @ORM\OneToMany(targetEntity=Client::class, mappedBy="channel", orphanRemoval=true)
     */
    private $clients;

    public function __construct()
    {
        $this->controlSets = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->controlGroups = new ArrayCollection();
        $this->groupQueues = new ArrayCollection();
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
            $controlGroup->setChannel($this);
        }

        return $this;
    }

    public function removeControlGroup(ControlGroup $controlGroup): self
    {
        if ($this->controlGroups->removeElement($controlGroup)) {
            // set the owning side to null (unless already changed)
            if ($controlGroup->getChannel() === $this) {
                $controlGroup->setChannel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupQueue[]
     */
    public function getGroupQueues(): Collection
    {
        return $this->groupQueues;
    }

    public function addGroupQueue(GroupQueue $groupQueue): self
    {
        if (!$this->groupQueues->contains($groupQueue)) {
            $this->groupQueues[] = $groupQueue;
            $groupQueue->setChannel($this);
        }

        return $this;
    }

    public function removeGroupQueue(GroupQueue $groupQueue): self
    {
        if ($this->groupQueues->removeElement($groupQueue)) {
            // set the owning side to null (unless already changed)
            if ($groupQueue->getChannel() === $this) {
                $groupQueue->setChannel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setChannel($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getChannel() === $this) {
                $client->setChannel(null);
            }
        }

        return $this;
    }
}
