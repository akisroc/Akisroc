<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @UniqueEntity("title")
 */
class Category
{
    public const TYPE_RP = 'rp';
    public const TYPE_HRP = 'hrp';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=63, nullable=false)
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="string", length=511, nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", length=15, nullable=false)
     */
    private ?string $type = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Board", mappedBy="category", cascade={"persist", "remove"})
     */
    private ?Collection $boards = null;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->boards = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     * @return self
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     * @return self
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getBoards(): ?Collection
    {
        return $this->boards;
    }

    /**
     * @param Board $board
     * @return self
     */
    public function addBoard(Board $board): self
    {
        if (!$this->boards->contains($board)) {
            $this->boards->add($board);
            $board->setCategory($this);
        }

        return $this;
    }

    /**
     * @param Board $board
     * @return self
     */
    public function removeBoard(Board $board): self
    {
        if ($this->boards->contains($board)) {
            $this->boards->removeElement($board);
            if ($board->getCategory() === $this) {
                $board->setCategory(null);
            }
        }

        return $this;
    }
}
