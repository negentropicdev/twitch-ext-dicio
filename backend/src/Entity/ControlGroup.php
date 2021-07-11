<?php

namespace App\Entity;

use App\Repository\ControlGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ControlGroupRepository::class)
 */
class ControlGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $queueGated;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Channel::class, inversedBy="controlGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $channel;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDynamic;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="controlGroups")
     */
    private $dynamic_user;

    /**
     * @ORM\ManyToOne(targetEntity=GroupQueue::class, inversedBy="groups")
     */
    private $groupQueue;

    /**
     * @ORM\OneToMany(targetEntity=ControlValue::class, mappedBy="control_group", orphanRemoval=true)
     */
    private $controlValues;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $active_user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\ManyToOne(targetEntity=ControlGroup::class, inversedBy="dynamic_groups")
     */
    private $cloned_from;

    /**
     * @ORM\OneToMany(targetEntity=ControlGroup::class, mappedBy="cloned_from")
     */
    private $dynamic_groups;

    public function __construct()
    {
        $this->controlValues = new ArrayCollection();
        $this->dynamic_groups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQueueGated(): ?bool
    {
        return $this->queueGated;
    }

    public function setQueueGated(bool $queueGated): self
    {
        $this->queueGated = $queueGated;

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

    public function getChannel(): ?Channel
    {
        return $this->channel;
    }

    public function setChannel(?Channel $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getIsDynamic(): ?bool
    {
        return $this->isDynamic;
    }

    public function setIsDynamic(bool $isDynamic): self
    {
        $this->isDynamic = $isDynamic;

        return $this;
    }

    public function getDynamicUser(): ?User
    {
        return $this->dynamic_user;
    }

    public function setDynamicUser(?User $dynamic_user): self
    {
        $this->dynamic_user = $dynamic_user;

        return $this;
    }

    public function getQueue(): ?GroupQueue
    {
        return $this->groupQueue;
    }

    public function setQueue(?GroupQueue $groupQueue): self
    {
        $this->groupQueue = $groupQueue;

        return $this;
    }

    /**
     * @return Collection|ControlValue[]
     */
    public function getControlValues(): Collection
    {
        return $this->controlValues;
    }

    public function addControlValue(ControlValue $controlValue): self
    {
        if (!$this->controlValues->contains($controlValue)) {
            $this->controlValues[] = $controlValue;
            $controlValue->setControlGroup($this);
        }

        return $this;
    }

    public function removeControlValue(ControlValue $controlValue): self
    {
        if ($this->controlValues->removeElement($controlValue)) {
            // set the owning side to null (unless already changed)
            if ($controlValue->getControlGroup() === $this) {
                $controlValue->setControlGroup(null);
            }
        }

        return $this;
    }

    public function getActiveUser(): ?User
    {
        return $this->active_user;
    }

    public function setActiveUser(?User $active_user): self
    {
        $this->active_user = $active_user;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getClonedFrom(): ?self
    {
        return $this->cloned_from;
    }

    public function setClonedFrom(?self $cloned_from): self
    {
        $this->cloned_from = $cloned_from;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getDynamicGroups(): Collection
    {
        return $this->dynamic_groups;
    }

    public function addDynamicGroup(self $dynamicGroup): self
    {
        if (!$this->dynamic_groups->contains($dynamicGroup)) {
            $this->dynamic_groups[] = $dynamicGroup;
            $dynamicGroup->setClonedFrom($this);
        }

        return $this;
    }

    public function removeDynamicGroup(self $dynamicGroup): self
    {
        if ($this->dynamic_groups->removeElement($dynamicGroup)) {
            // set the owning side to null (unless already changed)
            if ($dynamicGroup->getClonedFrom() === $this) {
                $dynamicGroup->setClonedFrom(null);
            }
        }

        return $this;
    }
}
