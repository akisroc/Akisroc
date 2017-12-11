<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @UniqueEntity("title")
 */
class Category
{
    const TYPE_RP = 'rp';
    const TYPE_HRP = 'hrp';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=63, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=511, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=15, nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="children", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Board", mappedBy="category", cascade={"persist", "remove"})
     */
    private $boards;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
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
     * @param string $title
     * @param string|null $description
     * @param string $type
     * @param Category|null $parent
     * @param iterable|null $children
     * @param iterable $boards
     * @return Category
     */
    static public function create(string $title, string $description = null, string $type,
                                  Category $parent = null, iterable $children = [],
                                  iterable $boards = []
    ): Category {
        $category = new Category();
        $category->setTitle($title);
        $category->setDescription($description);
        $category->setType($type);
        $category->setParent($parent);
        $category->addChildren($children);
        $category->addBoards($boards);

        return $category;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
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
     * @return Category|null
     */
    public function getParent(): ?Category
    {
        return $this->parent;
    }

    /**
     * @param Category|null $category
     * @return self
     */
    public function setParent(?Category $category): self
    {
        $this->parent = $category;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getChildren(): ?Collection
    {
        return $this->children;
    }

    /**
     * @param Collection|null $categories
     * @return self
     */
    public function setChildren(?Collection $categories): self
    {
        $this->children = $categories;

        return $this;
    }

    /**
     * @param Category|null $category
     * @return self
     */
    public function addChild(?Category $category): self
    {
        if ($category && !$this->children->contains($category)) {
            $this->children->add($category);
        }

        return $this;
    }

    /**
     * @param iterable $categories
     * @return self
     */
    public function addChildren(?iterable $categories): self
    {
        if ($categories) {
            foreach ($categories as $category) {
                $this->addChild($category);
            }
        }

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
     * @param Collection|null $boards
     * @return self
     */
    public function setBoards(?Collection $boards): self
    {
        $this->boards = $boards;

        return $this;
    }

    /**
     * @param Board|null $board
     * @return self
     */
    public function addBoard(?Board $board): self
    {
        if ($board && !$this->boards->contains($board)) {
            $this->boards->add($board);
        }

        return $this;
    }

    /**
     * @param iterable $boards
     * @return self
     */
    public function addBoards(?iterable $boards): self
    {
        if ($boards) {
            foreach ($boards as $board) {
                $this->addBoard($board);
            }
        }

        return $this;
    }
}
