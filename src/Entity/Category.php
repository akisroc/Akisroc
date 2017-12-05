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
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="children", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Topic", mappedBy="category", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $topics;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->topics = new ArrayCollection();
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
     * @param Category|null $parent
     * @param iterable|null $children
     * @return Category
     */
    static public function create(string $title, string $description = null,
                                  Category $parent = null, iterable $children = []
    ): Category {
        $category = new Category();
        $category->setTitle($title);
        $category->setDescription($description);
        $category->setParent($parent);
        $category->addChildren($children);

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
     * @return ArrayCollection|PersistentCollection|null
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * @param ArrayCollection|null $topics
     * @return self
     */
    public function setTopics(?ArrayCollection $topics): self
    {
        $this->topics = $topics;

        return $this;
    }

    /**
     * @param Topic|null $topic
     * @return self
     */
    public function addTopic(?Topic $topic): self
    {
        if ($topic && !$this->topics->contains($topic)) {
            $this->topics->add($topic);
        }

        return $this;
    }

    /**
     * @param iterable $topics
     * @return Category
     */
    public function addTopics(?iterable $topics): self
    {
        if ($topics) {
            foreach ($topics as $topic) {
                $this->addTopic($topic);
            }
        }

        return $this;
    }
}
