<?php

namespace App\Entity;

use App\Repository\GroupQueueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupQueueRepository::class)
 */
class GroupQueue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=ControlGroup::class, mappedBy="groupQueue")
     */
    private $groups;

    /**
     * @ORM\ManyToOne(targetEntity=Channel::class, inversedBy="groupQueues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $channel;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $queued_users = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $timeLimit;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startTime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|ControlGroup[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(ControlGroup $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->setGroupQueue($this);
        }

        return $this;
    }

    public function removeGroup(ControlGroup $group): self
    {
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getGroupQueue() === $this) {
                $group->setGroupQueue(null);
            }
        }

        return $this;
    }

    public function getChannel(): ?Channel
    {
        return $this->channel;
    }

    public function setChannel(?Channel $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getQueuedUsers(): ?array
    {
        return $this->queued_users;
    }

    public function setQueuedUsers(?array $queued_users): self
    {
        $this->queued_users = $queued_users;

        return $this;
    }

    public function getTimeLimit(): ?int
    {
        return $this->timeLimit;
    }

    public function setTimeLimit(int $timeLimit): self
    {
        $this->timeLimit = $timeLimit;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(?\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
