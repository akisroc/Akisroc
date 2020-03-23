<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BoardRepository")
 */
class Board
{
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="boards", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private ?Category $category = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Topic", mappedBy="board", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OrderBy({"id"="ASC"})
     */
    private ?Collection $topics = null;

    public function __construct()
    {
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
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return self
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getTopics(): ?Collection
    {
        return $this->topics;
    }

    /**
     * @return Topic|null
     */
    public function getLastTopic(): ?Topic
    {
        return $this->getTopics()->last();
    }

    /**
     * @param Topic $topic
     * @return self
     */
    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics->add($topic);
            $topic->setBoard($this);
        }

        return $this;
    }

    /**
     * @param Topic $topic
     * @return self
     */
    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->contains($topic)) {
            $this->topics->removeElement($topic);
            if ($topic->getBoard() === $this) {
                $topic->setBoard(null);
            }
        }
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        if ($category = $this->getCategory()) {
            return $category->getType();
        }

        return null;
    }
}
