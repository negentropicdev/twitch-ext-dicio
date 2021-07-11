<?php

namespace App\Entity;

use App\Repository\ControlRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ControlRepository::class)
 */
class Control implements \JsonSerializable
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
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $mappedControls = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $disabled;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $style;

    /**
     * @ORM\Column(type="json")
     */
    private $styleParams = [];

    public function jsonSerialize() {
      $json = [
        'id' => $this->id,
        'name' => $this->name,
        'style' => $this->style,
        'params' => $this->styleParams,
        'disabled' => $this->disabled,
        'controls' => $this->mappedControls,
      ];

      return $json;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMappedControls(): ?array
    {
        return $this->mappedControls;
    }

    public function setMappedControls(?array $mappedControls): self
    {
        $this->mappedControls = $mappedControls;

        return $this;
    }

    public function getDisabled(): ?bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(string $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getStyleParams(): ?array
    {
        return $this->styleParams;
    }

    public function setStyleParams(array $styleParams): self
    {
        $this->styleParams = $styleParams;

        return $this;
    }
}
