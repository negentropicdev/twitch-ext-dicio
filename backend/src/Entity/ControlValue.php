<?php

namespace App\Entity;

use App\Repository\ControlValueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ControlValueRepository::class)
 */
class ControlValue
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=ControlGroup::class, inversedBy="controlValues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $control_group;

    /**
     * @ORM\Column(type="float")
     */
    private $cur_value;

    /**
     * @ORM\Column(type="boolean")
     */
    private $readOnly;

    /**
     * @ORM\Column(type="float")
     */
    private $default_value;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_update;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $updated_by_type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $updated_by_id;

    /**
     * @ORM\ManyToOne(targetEntity=ControlValue::class, inversedBy="user_values")
     */
    private $cloned_from;

    /**
     * @ORM\OneToMany(targetEntity=ControlValue::class, mappedBy="cloned_from")
     */
    private $user_values;

    public function __construct()
    {
        $this->user_values = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getControlGroup(): ?ControlGroup
    {
        return $this->control_group;
    }

    public function setControlGroup(?ControlGroup $control_group): self
    {
        $this->control_group = $control_group;

        return $this;
    }

    public function getCurValue(): ?float
    {
        return $this->cur_value;
    }

    public function setCurValue(float $cur_value): self
    {
        $this->cur_value = $cur_value;

        return $this;
    }

    public function getReadOnly(): ?bool
    {
        return $this->readOnly;
    }

    public function setReadOnly(bool $readOnly): self
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    public function getDefaultValue(): ?float
    {
        return $this->default_value;
    }

    public function setDefaultValue(float $default_value): self
    {
        $this->default_value = $default_value;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->last_update;
    }

    public function setLastUpdate(?\DateTimeInterface $last_update): self
    {
        $this->last_update = $last_update;

        return $this;
    }

    public function getUpdatedByType(): ?string
    {
        return $this->updated_by_type;
    }

    public function setUpdatedByType(?string $updated_by_type): self
    {
        $this->updated_by_type = $updated_by_type;

        return $this;
    }

    public function getUpdatedById(): ?int
    {
        return $this->updated_by_id;
    }

    public function setUpdatedById(?int $updated_by_id): self
    {
        $this->updated_by_id = $updated_by_id;

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
    public function getUserValues(): Collection
    {
        return $this->user_values;
    }

    public function addUserValue(self $userValue): self
    {
        if (!$this->user_values->contains($userValue)) {
            $this->user_values[] = $userValue;
            $userValue->setClonedFrom($this);
        }

        return $this;
    }

    public function removeUserValue(self $userValue): self
    {
        if ($this->user_values->removeElement($userValue)) {
            // set the owning side to null (unless already changed)
            if ($userValue->getClonedFrom() === $this) {
                $userValue->setClonedFrom(null);
            }
        }

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
